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

use Phalcon\Di\Exception;
use Phalcon\Di\FactoryDefault\Cli as Di;
use Phalcon\Html\Escaper;
use Phalcon\Tests\Support\AbstractCliTestCase;

class OffsetGetTest extends AbstractCliTestCase
{
    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: offsetGet()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliOffsetGet(): void
    {
        $di    = new Di();
        $class = Escaper::class;
        $di->set('escaper', Escaper::class);

        $actual = $di->offsetGet('escaper');
        $this->assertInstanceOf($class, $actual);

        $actual = $di['escaper'];
        $this->assertInstanceOf($class, $actual);
    }

    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: offsetGet() exception array
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliOffsetGetExceptionArray(): void
    {
        $di = new Di();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Service 'non-exists' was not found in the dependency injection container"
        );

        $di['non-exists'];
    }

    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: offsetGet() exception offsetGet
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliOffsetGetExceptionOffsetGet(): void
    {
        $di = new Di();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Service 'non-exists' was not found in the dependency injection container"
        );

        $di->offsetGet('non-exists');
    }
}
