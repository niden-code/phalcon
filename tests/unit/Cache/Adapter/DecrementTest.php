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
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;

use function getOptionsLibmemcached;
use function outputDir;
use function uniqid;

final class DecrementTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cache\Adapter\* :: decrement()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterClear(
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
        $expected = $unknown;
        $actual   = $adapter->decrement($key);
        $this->assertSame($expected, $actual);

        if ('Stream' === $className) {
            self::safeDeleteDirectory(self::outputDir('ph-strm'));
        }
    }

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
                'Redis',
                Redis::class,
                self::getOptionsRedis(),
                -1,
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
}
