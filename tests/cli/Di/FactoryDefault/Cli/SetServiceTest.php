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
use Phalcon\Di\Service;
use Phalcon\Html\Escaper;
use Phalcon\Tests\Support\AbstractCliTestCase;

class SetServiceTest extends AbstractCliTestCase
{
    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: setService()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliSetRaw(): void
    {
        $di = new Cli();

        $expected = new Service(Escaper::class);

        $actual = $di->setService('escaper', $expected);

        $this->assertSame($expected, $actual);
    }
}
