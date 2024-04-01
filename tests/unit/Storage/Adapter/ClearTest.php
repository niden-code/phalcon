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

use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Libmemcached;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Storage\Adapter\ApcuDeleteFixture;
use Phalcon\Tests1\Fixtures\Storage\Adapter\ApcuIteratorFixture;
use Phalcon\Tests1\Fixtures\Storage\Adapter\StreamUnlinkFixture;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

use function uniqid;

#[RequiresPhpExtension('apcu')]
final class ClearTest extends AbstractUnitTestCase
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
     * Tests Phalcon\Storage\Adapter\Apcu :: clear() - delete error
     *
     * @return void
     *
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterApcuClearDeleteError(): void
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
     * Tests Phalcon\Storage\Adapter\Apcu :: clear() - iterator error
     *
     * @return void
     *
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterApcuClearIteratorError(): void
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
     * Tests Phalcon\Storage\Adapter\* :: clear()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterClear(
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
     * Tests Phalcon\Storage\Adapter\Stream :: clear() - cannot delete file
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamClearCannotDeleteFile(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new StreamUnlinkFixture(
            $serializer,
            [
                'storageDir' => self::outputDir(),
            ],
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
}
