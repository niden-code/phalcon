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

use Phalcon\Di\FactoryDefault\Cli as Di;
use Phalcon\Html\Escaper;
use Phalcon\Tests\Support\AbstractCliTestCase;

class RemoveTest extends AbstractCliTestCase
{
    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: remove()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliRemove(): void
    {
        $di = new Di();

        $di->set('escaper', Escaper::class);

        $actual = $di->has('escaper');
        $this->assertTrue($actual);

        $di->remove('escaper');

        $actual = $di->has('escaper');
        $this->assertFalse($actual);
    }
}
