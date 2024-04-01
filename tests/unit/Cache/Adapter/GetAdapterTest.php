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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Memcached as NativeMemcached;
use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;
use Redis as NativeRedis;

use function getOptionsLibmemcached;
use function getOptionsRedis;
use function outputDir;
use function sprintf;

final class GetAdapterTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cache\Adapter\* :: getAdapter()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterGetAdapter(
        string $class,
        array $options,
        mixed $expected
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $actual   = $adapter->getAdapter();

        if (null === $expected) {
            $this->assertNull($actual);
        } else {
            $this->assertInstanceOf($expected, $actual);
        }
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
                null,
            ],
            [
                Libmemcached::class,
                self::getOptionsLibmemcached(),
                NativeMemcached::class,
            ],
            [
                Memory::class,
                [],
                null,
            ],
            [
                Redis::class,
                self::getOptionsRedis(),
                NativeRedis::class,
            ],
            [
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
                null,
            ],
        ];
    }
}
