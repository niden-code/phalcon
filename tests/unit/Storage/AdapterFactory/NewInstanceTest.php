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

use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\Exception;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Unit\Storage\AbstractStorageTestCase;

use function uniqid;

final class NewInstanceTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\AdapterFactory :: newInstance()
     *
     * @dataProvider getExamples
     *
     * @return void
     * @throws Exception
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterFactoryNewInstance(
        string $adapterClass,
        array $options,
        string $extension,
        string $name
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new AdapterFactory($serializer);

        $service = $adapter->newInstance($name, $options);

        $this->assertInstanceOf($adapterClass, $service);
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
        $this->expectExceptionMessage(
            'Service ' . $name . ' is not registered'
        );

        $serializer = new SerializerFactory();
        $adapter    = new AdapterFactory($serializer);

        $service = $adapter->newInstance($name);
    }
}
