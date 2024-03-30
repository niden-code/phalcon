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

namespace Phalcon\Tests\Unit\Storage\Adapter;

use Codeception\Stub;
use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Libmemcached;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Storage\Adapter\StreamGetContentsFixture;

use function getOptionsLibmemcached2;
use function getOptionsRedis2;
use function uniqid;

final class HasTest extends AbstractUnitTestCase
{
    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                Apcu::class,
                [],
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
            ],
            [
                Memory::class,
                [],
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
            ],
        ];
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: has()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterHas(
        string $class,
        array $options,
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid();

        $actual = $adapter->has($key);
        $this->assertFalse($actual);

        $adapter->set($key, 'test');
        $actual = $adapter->has($key);
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: has() - cannot open file
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamHasCannotOpenFile(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = Stub::construct(
            Stream::class,
            [
                $serializer,
                [
                    'storageDir' => self::outputDir(),
                ],
            ],
            [
                'phpFopen' => false,
            ]
        );

        $key    = uniqid();
        $actual = $adapter->set($key, 'test');
        $this->assertTrue($actual);

        $actual = $adapter->has($key);
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: has() - empty payload
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamHasEmptyPayload(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new StreamGetContentsFixture(
            $serializer,
            [
                'storageDir' => self::outputDir(),
            ],
        );

        $key    = uniqid();
        $actual = $adapter->set($key, 'test');
        $this->assertTrue($actual);

        $actual = $adapter->has($key);
        $this->assertFalse($actual);
    }
}
