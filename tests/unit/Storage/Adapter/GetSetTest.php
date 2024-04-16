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
use stdClass;

use function array_merge;
use function uniqid;

final class GetSetTest extends AbstractUnitTestCase
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
                null,
            ],
            [
                Apcu::class,
                [],
                true,
            ],
            [
                Apcu::class,
                [],
                false,
            ],
            [
                Apcu::class,
                [],
                123456,
            ],
            [
                Apcu::class,
                [],
                123.456,
            ],
            [
                Apcu::class,
                [],
                uniqid(),
            ],
            [
                Apcu::class,
                [],
                new stdClass(),
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                null,
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                true,
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                false,
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                123456,
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                123.456,
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                uniqid(),
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                new stdClass(),
            ],
            [
                Libmemcached::class,
                array_merge(
                    self::getOptionsLibmemcached(),
                    [
                        'defaultSerializer' => 'Base64',
                    ]
                ),
                uniqid(),
            ],
            [
                Memory::class,
                [],
                null,
            ],
            [
                Memory::class,
                [],
                true,
            ],
            [
                Memory::class,
                [],
                false,
            ],
            [
                Memory::class,
                [],
                123456,
            ],
            [
                Memory::class,
                [],
                123.456,
            ],
            [
                Memory::class,
                [],
                uniqid(),
            ],
            [
                Memory::class,
                [],
                new stdClass(),
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                null,
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                true,
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                false,
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                123456,
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                123.456,
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                uniqid(),
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                new stdClass(),
            ],
            [
                Redis::class,
                array_merge(
                    self::getOptionsRedis(),
                    [
                        'defaultSerializer' => 'Base64',
                    ]
                ),
                uniqid(),
            ],
            [
                Redis::class,
                array_merge(
                    self::getOptionsRedis(),
                    [
                        'persistent' => true,
                    ]
                ),
                uniqid(),
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                null,
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                true,
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                false,
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                123456,
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                123.456,
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                uniqid(),
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                new stdClass(),
            ],
        ];
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: get()/set()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetSet(
        string $class,
        array $options,
        mixed $value
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid('k-');

        $result = $adapter->set($key, $value);
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertTrue($result);

        /**
         * This will issue delete
         */
        $result = $adapter->set($key, $value, 0);
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertFalse($result);
    }
}
