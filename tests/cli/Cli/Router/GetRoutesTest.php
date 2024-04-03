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

namespace Phalcon\Tests\Cli\Cli\Router;

use Phalcon\Cli\Router;
use Phalcon\Cli\Router\Route;
use Phalcon\Tests\Support\AbstractCliTestCase;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;

class GetRoutesTest extends AbstractCliTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Cli\Router :: getRoutes()
     *
     * @author Sid Roberts <https://github.com/SidRoberts>
     * @since  2019-06-03
     */
    public function testCliRouterGetRoutes(): void
    {
        $this->setNewCliFactoryDefault();

        Route::reset();
        $router = new Router(false);

        $expected = [];
        $actual   = $router->getRoutes();
        $this->assertSame($expected, $actual);

        $usersFind = $router->add('api users find')
                            ->setName('usersFind')
        ;
        $usersAdd  = $router->add('api users add')
                            ->setName('usersAdd')
        ;

        $expected = [
            0 => $usersFind,
            1 => $usersAdd,
        ];
        $actual   = $router->getRoutes();
        $this->assertSame($expected, $actual);
    }
}
