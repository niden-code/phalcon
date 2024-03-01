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

namespace Phalcon\Tests\Unit\Annotations\AnnotationsFactory;

use Phalcon\Annotations\Adapter\Apcu;
use Phalcon\Annotations\Adapter\Memory;
use Phalcon\Annotations\Adapter\Stream;
use Phalcon\Annotations\AnnotationsFactory;
use Phalcon\Annotations\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;

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
            ],
            [
                'memory',
                Memory::class,
            ],
            [
                'stream',
                Stream::class,
            ],
        ];
    }

    /**
     * Tests Phalcon\Annotations\AdapterFactory :: newInstance()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-05-19
     */
    public function testAnnotationsAdapterFactoryNewInstance(
        string $name,
        string $class
    ): void {
        $factory = new AnnotationsFactory();
        $image   = $factory->newInstance($name);

        $this->assertInstanceOf($class, $image);
    }

    /**
     * Tests Phalcon\Annotations\AdapterFactory :: newInstance() - exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2022-08-02
     */
    public function testAnnotationsAdapterFactoryNewInstanceException(): void
    {
        $name = uniqid('service-');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service ' . $name . ' is not registered');

        $factory = new AnnotationsFactory();
        $factory->newInstance($name);
    }
}
