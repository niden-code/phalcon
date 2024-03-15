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

namespace Phalcon\Tests\Cli\Di\FactoryDefault\Cli;

use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Tests\Support\AbstractCliTestCase;
use Phalcon\Tests1\Fixtures\Traits\CliTrait2;

class ConstructTest extends AbstractCliTestCase
{
    use CliTrait2;

    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: __construct() - Check services
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     *
     * @dataProvider getServices
     */
    public function testDiFactoryDefaultCliConstructServices(
        string $service,
        string $class
    ): void {
        $container = new Cli();

        if ('sessionBag' === $service) {
            $params = ['someName'];
        } else {
            $params = null;
        }

        $actual = $container->get($service, $params);
        $this->assertInstanceOf($class, $actual);
    }

    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliConstruct(): void
    {
        $container = new Cli();
        $services  = $this->getServices();

        $expected = count($services);
        $actual   = count($container->getServices());
        $this->assertEquals($expected, $actual);
    }
}
