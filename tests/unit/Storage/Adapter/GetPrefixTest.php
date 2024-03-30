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

use function array_merge;
use function getOptionsRedis2;

final class GetPrefixTest extends AbstractUnitTestCase
{
    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                Apcu::class,
                [
                ],
                'ph-apcu-',
            ],
            [
                Apcu::class,
                [
                    'prefix' => '',
                ],
                '',
            ],
            [
                Apcu::class,
                [
                    'prefix' => 'my-prefix',
                ],
                'my-prefix',
            ],
            [
                Libmemcached::class,
                array_merge(
                    self::getOptionsLibmemcached(),
                    [
                    ]
                ),
                'ph-memc-',
            ],
            [
                Libmemcached::class,
                array_merge(
                    self::getOptionsLibmemcached(),
                    [
                        'prefix' => '',
                    ]
                ),
                '',
            ],
            [
                Libmemcached::class,
                array_merge(
                    self::getOptionsLibmemcached(),
                    [
                        'prefix' => 'my-prefix',
                    ]
                ),
                'my-prefix',
            ],
            [
                Memory::class,
                [
                ],
                'ph-memo-',
            ],
            [
                Memory::class,
                [
                    'prefix' => '',
                ],
                '',
            ],
            [
                Memory::class,
                [
                    'prefix' => 'my-prefix',
                ],
                'my-prefix',
            ],
            [
                Redis::class,
                array_merge(
                    self::getOptionsRedis(),
                    [
                    ]
                ),
                'ph-reds-',
            ],
            [
                Redis::class,
                array_merge(
                    self::getOptionsRedis(),
                    [
                        'prefix' => '',
                    ]
                ),
                '',
            ],
            [
                Redis::class,
                array_merge(
                    self::getOptionsRedis(),
                    [
                        'prefix' => 'my-prefix',
                    ]
                ),
                'my-prefix',
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                'ph-strm',
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                    'prefix'     => '',
                ],
                '',
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                    'prefix'     => 'my-prefix',
                ],
                'my-prefix',
            ],
        ];
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: getPrefix()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetSetPrefix(
        string $class,
        array $options,
        string $expected
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $actual = $adapter->getPrefix();
        $this->assertSame($expected, $actual);
    }
}
