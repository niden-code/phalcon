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

use Memcached as NativeMemcached;
use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Libmemcached;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Redis as NativeRedis;

use function getOptionsLibmemcached2;
use function getOptionsRedis2;

final class GetAdapterTest extends AbstractUnitTestCase
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

    /**
     * Tests Phalcon\Storage\Adapter\* :: getAdapter()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetAdapter(
        string $class,
        array $options,
        mixed $expected,
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $actual = $adapter->getAdapter();

        if (null === $expected) {
            $this->assertNull($actual);
        } else {
            $this->assertInstanceOf($expected, $actual);
        }
    }
}
