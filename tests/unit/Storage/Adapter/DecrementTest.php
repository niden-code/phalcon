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
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

use function getOptionsLibmemcached2;
use function getOptionsRedis2;
use function uniqid;

final class DecrementTest extends AbstractUnitTestCase
{
    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'Apcu',
                Apcu::class,
                [],
                -1,
            ],
            [
                'Libmemcached',
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                false,
            ],
            [
                'Memory',
                Memory::class,
                [],
                false,
            ],
            [
                'Stream',
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                false,
            ],
        ];
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: decrement()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterClear(
        string $className,
        string $class,
        array $options,
        mixed $unknown
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key    = uniqid();
        $result = $adapter->set($key, 100);
        $this->assertTrue($result);

        $expected = 99;
        $actual   = $adapter->decrement($key);
        $this->assertSame($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertSame($expected, $actual);

        $expected = 90;
        $actual   = $adapter->decrement($key, 9);
        $this->assertSame($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertSame($expected, $actual);

        /**
         * unknown key
         */
        $key      = uniqid();
        $expected = $unknown;
        $actual   = $adapter->decrement($key);
        $this->assertSame($expected, $actual);

        if ('Stream' === $className) {
            self::safeDeleteDirectory(self::outputDir('ph-strm'));
        }
    }

    /**
     * Tests Phalcon\Storage\Adapter\Redis :: decrement()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('redis')]
    public function testStorageAdapterRedisDecrement(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Redis($serializer, self::getOptionsRedis());

        $key      = uniqid();
        $expected = 100;
        $actual   = $adapter->increment($key, 100);
        $this->assertSame($expected, $actual);

        $expected = 99;
        $actual   = $adapter->decrement($key);
        $this->assertSame($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        $expected = 90;
        $actual   = $adapter->decrement($key, 9);
        $this->assertSame($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        /**
         * unknown key
         */
        $key      = uniqid();
        $expected = -9;
        $actual   = $adapter->decrement($key, 9);
        $this->assertSame($expected, $actual);
    }
}
