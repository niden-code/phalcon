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

final class DeleteTest extends AbstractUnitTestCase
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
     * Tests Phalcon\Cache\Adapter\* :: delete()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterDelete(
        string $class,
        array $options
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid();
        $adapter->set($key, 'test');
        $actual = $adapter->has($key);
        $this->assertTrue($actual);

        $actual = $adapter->delete($key);
        $this->assertTrue($actual);

        $actual = $adapter->has($key);
        $this->assertFalse($actual);

        /**
         * Call clear twice to ensure it returns false
         */
        $actual = $adapter->delete($key);
        $this->assertFalse($actual);

        /**
         * Delete unknown
         */
        $key    = uniqid();
        $actual = $adapter->delete($key);
        $this->assertFalse($actual);
    }
}
