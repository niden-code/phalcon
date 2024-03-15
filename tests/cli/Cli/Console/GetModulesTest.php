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

namespace Phalcon\Tests\Cli\Cli\Console;

use Phalcon\Cli\Console as CliConsole;
use Phalcon\Di\FactoryDefault\Cli as DiFactoryDefault;
use Phalcon\Tests\Support\AbstractCliTestCase;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;
use Phalcon\Tests1\Modules\Backend\Module as BackendModule;
use Phalcon\Tests1\Modules\Frontend\Module as FrontendModule;

class GetModulesTest extends AbstractCliTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Cli\Console :: getModules()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2018-12-26
     */
    public function testCliConsoleGetModules(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $definition = [
            'frontend' => [
                'className' => FrontendModule::class,
                'path'      => self::dataDir('fixtures/modules/frontend/Module.php'),
            ],
            'backend'  => [
                'className' => BackendModule::class,
                'path'      => self::dataDir('fixtures/modules/backend/Module.php'),
            ],
        ];

        $console->registerModules($definition);

        $expected = $definition;
        $actual   = $console->getModules();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Cli\Console :: getModules() - empty
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2018-12-26
     */
    public function testCliConsoleGetModulesEmpty(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $expected = [];
        $actual   = $console->getModules();
        $this->assertSame($expected, $actual);
    }
}
