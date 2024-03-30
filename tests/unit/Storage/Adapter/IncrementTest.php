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

final class IncrementTest extends AbstractUnitTestCase
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
                1,
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
     * Tests Phalcon\Storage\Adapter\* :: increment()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterIncrement(
        string $className,
        string $class,
        array $options,
        mixed $unknown
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key    = uniqid();
        $result = $adapter->set($key, 1);
        $this->assertTrue($result);

        $expected = 2;
        $actual   = $adapter->increment($key);
        $this->assertSame($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertSame($expected, $actual);

        $expected = 20;
        $actual   = $adapter->increment($key, 18);
        $this->assertSame($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertSame($expected, $actual);

        /**
         * unknown key
         */
        $key      = uniqid();
        $expected = $unknown;
        $actual   = $adapter->increment($key);
        $this->assertSame($expected, $actual);

        if ('Stream' === $className) {
            $this->safeDeleteDirectory(self::outputDir('ph-strm'));
        }
    }

    /**
     * Tests Phalcon\Storage\Adapter\Redis :: increment()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('redis')]
    public function testStorageAdapterRedisIncrement(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Redis($serializer, self::getOptionsRedis());

        $key      = uniqid();
        $expected = 1;
        $actual   = $adapter->increment($key, 1);
        $this->assertEquals($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        $expected = 2;
        $actual   = $adapter->increment($key);
        $this->assertEquals($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        $expected = 10;
        $actual   = $adapter->increment($key, 8);
        $this->assertEquals($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        /**
         * unknown key
         */
        $key      = uniqid();
        $expected = 1;
        $actual   = $adapter->increment($key);
        $this->assertSame($expected, $actual);
    }
}
