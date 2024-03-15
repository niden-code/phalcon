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

namespace Phalcon\Tests\Cli\Cli\Router\Route;

use Phalcon\Cli\Router;
use Phalcon\Cli\Router\Route;
use Phalcon\Tests\Support\AbstractCliTestCase;
use Phalcon\Tests1\Fixtures\Traits\CliTrait2;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;

class GetPathsTest extends AbstractCliTestCase
{
    use CliTrait2;
    use DiTrait2;

    /**
     * @dataProvider providerShortPaths
     *
     * @return void
     */
    public function testCliRouterRouteGetPathsShortPaths(
        string $path,
        array $expected
    ): void {
        $this->setNewCliFactoryDefault();

        Route::reset();

        $router = new Router(false);

        $route  = $router->add('route', $path);
        $actual = $route->getPaths();
        $this->assertSame($expected, $actual);
    }
}
