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

use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Support\AbstractUnitTestCase;

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
     * Tests Phalcon\Cache\Adapter\* :: get()/set()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterGetSetWithZeroTtl(
        string $class,
        array $options
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid();

        $result = $adapter->set($key, "test");
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertTrue($result);

        /**
         * This will issue delete
         */
        $result = $adapter->set($key, "test", 0);
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertFalse($result);
    }
}
