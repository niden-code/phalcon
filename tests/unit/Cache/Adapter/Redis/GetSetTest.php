<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Cache\Adapter\Redis;

use Phalcon\Cache\Adapter\Redis;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use stdClass;

use function array_merge;
use function uniqid;

#[RequiresPhpExtension('redis')]
final class GetSetTest extends AbstractUnitTestCase
{
    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        return [
            [
                'random string',
            ],
            [
                123456,
            ],
            [
                123.456,
            ],
            [
                true,
            ],
            [
                new stdClass(),
            ],
        ];
    }

    /**
     * Tests Phalcon\Cache\Adapter\Redis :: get()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterRedisGetSet(
        mixed $value
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new Redis($serializer, self::getOptionsRedis());

        $key    = uniqid();
        $actual = $adapter->set($key, $value);
        $this->assertTrue($actual);

        $expected = $value;
        $actual   = $adapter->get($key);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Phalcon\Cache\Adapter\Redis :: get()/set() - custom serializer
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterRedisGetSetCustomSerializer(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Redis(
            $serializer,
            array_merge(
                self::getOptionsRedis(),
                [
                    'defaultSerializer' => 'Base64',
                ]
            )
        );

        $key    = uniqid();
        $source = 'Phalcon Framework';

        $actual = $adapter->set($key, $source);
        $this->assertTrue($actual);

        $expected = $source;
        $actual   = $adapter->get($key);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Cache\Adapter\Redis :: get() - failed auth
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterRedisGetSetFailedAuth(): void
    {
        $this->expectException(StorageException::class);
        $this->expectExceptionMessage(
            'Failed to authenticate with the Redis server'
        );

        $serializer = new SerializerFactory();
        $adapter    = new Redis(
            $serializer,
            array_merge(
                self::getOptionsRedis(),
                [
                    'auth' => 'something',
                ]
            )
        );

        $adapter->get('test');
    }

    /**
     * Tests Phalcon\Cache\Adapter\Redis :: get() - persistent
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterRedisGetSetPersistent(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Redis(
            $serializer,
            array_merge(
                self::getOptionsRedis(),
                [
                    'persistent' => true,
                ]
            )
        );

        $key    = uniqid();
        $actual = $adapter->set($key, 'test');
        $this->assertTrue($actual);

        $expected = 'test';
        $actual   = $adapter->get($key);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Cache\Adapter\Redis :: get() - wrong index
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterRedisGetSetWrongIndex(): void
    {
        $this->expectException(StorageException::class);
        $this->expectExceptionMessage('Redis server selected database failed');

        $serializer = new SerializerFactory();
        $adapter    = new Redis(
            $serializer,
            array_merge(
                self::getOptionsRedis(),
                [
                    'index' => 99,
                ]
            )
        );

        $adapter->get('test');
    }
}
