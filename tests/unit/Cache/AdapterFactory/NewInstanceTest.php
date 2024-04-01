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

namespace Phalcon\Tests\Unit\Cache\AdapterFactory;

use Phalcon\Cache\Adapter\Apcu;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Adapter\Memory;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Cache\AdapterFactory;
use Phalcon\Cache\Exception\Exception;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function uniqid;

final class NewInstanceTest extends AbstractUnitTestCase
{
    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'apcu',
                Apcu::class,
                [],
            ],
            [
                'libmemcached',
                Libmemcached::class,
                self::getOptionsLibmemcached(),
            ],
            [
                'memory',
                Memory::class,
                [],
            ],
            [
                'redis',
                Redis::class,
                self::getOptionsRedis(),
            ],
            [
                'stream',
                Stream::class,
                [
                    'storageDir' => self::outputDir(),
                ],
            ],
        ];
    }

    /**
     * Tests Phalcon\Cache\AdapterFactory :: newInstance()
     *
     * @dataProvider providerExamples
     *
     * @param string $name
     * @param string $class
     * @param array  $options
     *
     * @return void
     * @throws \Exception
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterFactoryNewInstance(
        string $name,
        string $class,
        array $options
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new AdapterFactory($serializer);

        $service = $adapter->newInstance($name, $options);

        $this->assertInstanceOf($class, $service);
    }

    /**
     * Tests Phalcon\Storage\SerializerFactory :: newInstance() - exception
     *
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheSerializerFactoryNewInstanceException(): void
    {
        $name = uniqid();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service ' . $name . ' is not registered');

        $serializer = new SerializerFactory();
        $adapter    = new AdapterFactory($serializer);

        $service = $adapter->newInstance($name);
    }
}
