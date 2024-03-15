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

use Phalcon\Config\Config;
use Phalcon\Di\FactoryDefault\Cli as Di;
use Phalcon\Tests\Support\AbstractCliTestCase;

class LoadFromPhpTest extends AbstractCliTestCase
{
    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: loadFromPhp()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliLoadFromPhp(): void
    {
        $di = new Di();

        // load php
        $di->loadFromPhp(self::dataDir('fixtures/Di/services.php'));

        // there are 3 new + 11 from Default
        $this->assertCount(12, $di->getServices());

        // check some services
        $actual = $di->get('config');
        $this->assertInstanceOf(Config::class, $actual);

        $this->assertTrue($di->has('config'));
        $this->assertTrue($di->has('unit-test'));
        $this->assertTrue($di->has('component'));
    }
}
