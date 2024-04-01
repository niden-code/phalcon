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

namespace Phalcon\Tests\Unit\Cache\Adapter;

use Codeception\Example;
use Codeception\Stub;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;

use Phalcon\Tests1\Fixtures\Storage\Adapter\ApcuDeleteFixture;
use Phalcon\Tests1\Fixtures\Storage\Adapter\ApcuIteratorFixture;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

use function uniqid;

final class ClearTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cache\Adapter\Apcu :: clear() - iterator error
     *
     * @return void
     *
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('apcu')]
    public function testCacheAdapterApcuClearIteratorError(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new ApcuIteratorFixture($serializer);

        $key1 = uniqid();
        $key2 = uniqid();
        $adapter->set($key1, 'test');
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, 'test');
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Cache\Adapter\Apcu :: clear() - delete error
     *
     * @return void
     *
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('apcu')]
    public function testCacheAdapterApcuClearDeleteError(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new ApcuDeleteFixture($serializer);

        $key1 = uniqid();
        $key2 = uniqid();
        $adapter->set($key1, 'test');
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, 'test');
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Cache\Adapter\Stream :: clear() - cannot delete file
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterStreamClearCannotDeleteFile(): void
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
                'phpUnlink' => false,
            ]
        );

        $key1 = uniqid();
        $key2 = uniqid();
        $adapter->set($key1, 'test');
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, 'test');
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertFalse($actual);

        self::safeDeleteDirectory(self::outputDir('ph-strm'));
    }

    /**
     * Tests Phalcon\Cache\Adapter\* :: clear()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterClear(
        string $class,
        array $options
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key1 = uniqid();
        $key2 = uniqid();
        $adapter->set($key1, 'test');
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, 'test');
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertTrue($actual);

        $actual = $adapter->has($key1);
        $this->assertFalse($actual);

        $actual = $adapter->has($key2);
        $this->assertFalse($actual);

        /**
         * Call clear twice to ensure it returns true
         */
        $actual = $adapter->clear();
        $this->assertTrue($actual);
    }

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
}
