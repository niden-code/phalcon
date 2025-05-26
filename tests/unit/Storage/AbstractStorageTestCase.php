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

namespace Phalcon\Tests\Unit\Storage;

use ArrayObject;
use Memcached as NativeMemcached;
use Redis as NativeRedis;
use RedisCluster as NativeRedisCluster;
use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\RedisCluster;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Adapter\Weak;
use Phalcon\Tests\AbstractUnitTestCase;
use SplObjectStorage;
use SplQueue;
use stdClass;

use function array_merge;
use function getOptionsRedis;
use function getOptionsRedisCluster;
use function outputDir;
use function uniqid;

abstract class AbstractStorageTestCase extends AbstractUnitTestCase
{
    /**
     * @return array[]
     */
    public static function getExamples(): array
    {
        return [
            [
                Apcu::class,        // Class
                [],                 // Options
                'apcu',             // Extension
                'apcu',             // Name
                -1,                 // Decrement
                1,                  // Increment
                null,               // GetAdapter
                'ph-apcu-',         // Prefix
            ],
//            [
//                Libmemcached::class,
//                getOptionsLibmemcached(),
//                'memcached',
//                'libmemcached',
//                false,
//                false,
//                NativeMemcached::class,
//                'ph-memc-',
//            ],
            [
                Memory::class,
                [],
                '',
                'memory',
                false,
                false,
                null,
                'ph-memo-',
            ],
//            [
//                Redis::class,
//                getOptionsRedis(),
//                'redis',
//                'redis',
//                -1,
//                1,
//                NativeRedis::class,
//               'ph-reds-',
//            ],
//            [
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                'redis',
//                'rediscluster',
//                -1,
//                1,
//                NativeRedisCluster::class,
//                'ph-redc-',
//            ],
            [
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                '',
                'stream',
                false,
                false,
                null,
                'ph-strm-',
            ],
            [
                Weak::class,
                [],
                '',
                'weak',
                false,
                false,
                null,
                'ph-weak-',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function getGetSetExamples(): array
    {
        return [
            [
                'apcu',
                Apcu::class,
                [],
                null,
            ],
            [
                'apcu',
                Apcu::class,
                [],
                true,
            ],
            [
                'apcu',
                Apcu::class,
                [],
                false,
            ],
            [
                'apcu',
                Apcu::class,
                [],
                123456,
            ],
            [
                'apcu',
                Apcu::class,
                [],
                123.456,
            ],
            [
                'apcu',
                Apcu::class,
                [],
                uniqid(),
            ],
            [
                'apcu',
                Apcu::class,
                [],
                new stdClass(),
            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                getOptionsLibmemcached(),
            //                null,
            //            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                getOptionsLibmemcached(),
            //                true,
            //            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                getOptionsLibmemcached(),
            //                false,
            //            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                getOptionsLibmemcached(),
            //                123456,
            //            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                getOptionsLibmemcached(),
            //                123.456,
            //            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                getOptionsLibmemcached(),
            //                uniqid(),
            //            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                getOptionsLibmemcached(),
            //                new stdClass(),
            //            ],
            //            [
            //                'memcached',
            //                Libmemcached::class,
            //                array_merge(
            //                    getOptionsLibmemcached(),
            //                    [
            //                        'defaultSerializer' => 'Base64',
            //                    ]
            //                ),
            //                uniqid(),
            //            ],
            [
                '',
                Memory::class,
                [],
                null,
            ],
            [
                '',
                Memory::class,
                [],
                true,
            ],
            [
                '',
                Memory::class,
                [],
                false,
            ],
            [
                '',
                Memory::class,
                [],
                123456,
            ],
            [
                '',
                Memory::class,
                [],
                123.456,
            ],
            [
                '',
                Memory::class,
                [],
                uniqid(),
            ],
            [
                '',
                Memory::class,
                [],
                new stdClass(),
            ],
//            [
//                'redis',
//                Redis::class,
//                getOptionsRedis(),
//                null,
//            ],
//            [
//                'redis',
//                Redis::class,
//                getOptionsRedis(),
//                true,
//            ],
//            [
//                'redis',
//                Redis::class,
//                getOptionsRedis(),
//                false,
//            ],
//            [
//                'redis',
//                Redis::class,
//                getOptionsRedis(),
//                123456,
//            ],
//            [
//                'redis',
//                Redis::class,
//                getOptionsRedis(),
//                123.456,
//            ],
//            [
//                'redis',
//                Redis::class,
//                getOptionsRedis(),
//                uniqid(),
//            ],
//            [
//                'redis',
//                Redis::class,
//                getOptionsRedis(),
//                new stdClass(),
//            ],
//            [
//                'redis',
//                Redis::class,
//                array_merge(
//                    getOptionsRedis(),
//                    [
//                        'defaultSerializer' => 'Base64',
//                    ]
//                ),
//                uniqid(),
//            ],
//            [
//                'redis',
//                Redis::class,
//                array_merge(
//                    getOptionsRedis(),
//                    [
//                        'persistent' => true,
//                    ]
//                ),
//                uniqid(),
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                null,
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                true,
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                false,
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                123456,
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                123.456,
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                uniqid(),
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                getOptionsRedisCluster(),
//                new stdClass(),
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                array_merge(
//                    getOptionsRedisCluster(),
//                    [
//                        'defaultSerializer' => 'Base64',
//                    ]
//                ),
//                uniqid(),
//            ],
//            [
//                'redis',
//                RedisCluster::class,
//                array_merge(
//                    getOptionsRedisCluster(),
//                    [
//                        'persistent' => true,
//                    ]
//                ),
//                uniqid(),
//            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                null,
            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                true,
            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                false,
            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                123456,
            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                123.456,
            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                uniqid(),
            ],
            [
                '',
                Stream::class,
                [
                    'storageDir' => outputDir(),
                ],
                new stdClass(),
            ],
            [
                '',
                Weak::class,
                [],
                new stdClass(),
            ],
            [
                '',
                Weak::class,
                [],
                new stdClass(),
            ],
            [
                '',
                Weak::class,
                [],
                new ArrayObject(),
            ],
            [
                '',
                Weak::class,
                [],
                new SplObjectStorage(),
            ],
            [
                '',
                Weak::class,
                [],
                new SplQueue(),
            ],
        ];
    }

}
