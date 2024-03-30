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

namespace Phalcon\Tests\Unit\Storage\AdapterFactory;

use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Libmemcached;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\Exception;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function getOptionsLibmemcached2;
use function getOptionsRedis2;
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
     * Tests Phalcon\Storage\AdapterFactory :: newInstance()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterFactoryNewInstance(
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
    public function testStorageSerializerFactoryNewInstanceException(): void
    {
        $name = uniqid();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service ' . $name . ' is not registered');

        $serializer = new SerializerFactory();
        $adapter    = new AdapterFactory($serializer);

        $service = $adapter->newInstance($name);
    }
}
