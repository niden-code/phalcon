<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests1\Modules\Backend;

use Phalcon\Di\DiInterface;
use Phalcon\Autoload\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;
use Phalcon\Tests\Support\DirTrait;

use function dataDir;

/**
 * \Phalcon\Tests\Modules\Backend\Module
 * Backend Module
 *
 * @copyright (c) 2011-2017 Phalcon Team
 * @link          https://www.phalcon.io
 * @author        Andres Gutierrez <andres@phalcon.io>
 * @author        Nikolaos Dimopoulos <nikos@phalcon.io>
 *
 * The contents of this file are subject to the New BSD License that is
 * bundled with this package in the file LICENSE.txt
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@phalcon.io
 * so that we can send you a copy immediately.
 */
class Module implements ModuleDefinitionInterface
{
    use DirTrait;

    public function registerAutoloaders(DiInterface $di = null)
    {
    }

    public function registerServices(DiInterface $di)
    {
        $di->set(
            'view',
            function () {
                $view = new View();

                $view->setViewsDir(
                    self::dataDir('fixtures/modules/backend/views/')
                );

                return $view;
            }
        );

        /**
         * @var Loader $loader
         */
        $loader = new Loader();
        $loader->setNamespaces(
            [
                'Phalcon\Tests1\Modules\Backend\Tasks' =>
                    self::dataDir('fixtures/modules/backend/tasks/')
            ]
        );
        $loader->register();

        $di->set('loader', $loader);
    }
}
