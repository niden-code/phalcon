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

use Exception;
use Phalcon\Cli\Console as CliConsole;
use Phalcon\Cli\Console\Exception as ConsoleException;
use Phalcon\Cli\Dispatcher\Exception as DispatcherException;
use Phalcon\Cli\Router\Exception as RouterException;
use Phalcon\Di\FactoryDefault\Cli as DiFactoryDefault;
use Phalcon\Events\Event;
use Phalcon\Events\Exception as EventsException;
use Phalcon\Tests\Support\AbstractCliTestCase;
use Phalcon\Tests1\Fixtures\Tasks\Issue787Task;
use Phalcon\Tests1\Modules\Backend\Module as BackendModule;
use Phalcon\Tests1\Modules\Frontend\Module as FrontendModule;

use function ob_end_clean;
use function ob_start;
use function shell_exec;

use const PHP_OS_FAMILY;

class HandleTest extends AbstractCliTestCase
{
    /**
     * @return array
     */
    public static function providerHandle(): array
    {
        return [
            [
                [],
                'main',
                'main',
                [],
                'mainAction',
            ],
            [
                [
                    'task' => 'echo',
                ],
                'echo',
                'main',
                [],
                'echoMainAction',
            ],
            [
                [
                    'task'   => 'main',
                    'action' => 'hello',
                ],
                'main',
                'hello',
                [],
                'Hello !',
            ],
            [
                [
                    'task'   => 'main',
                    'action' => 'hello',
                    'World',
                    '#####',
                ],
                'main',
                'hello',
                [
                    'World',
                    '#####',
                ],
                'Hello World#####',
            ],
        ];
    }

    /**
     * Tests Phalcon\Cli\Console :: handle()
     *
     * @dataProvider providerHandle
     *
     * @param array  $arguments
     * @param string $taskName
     * @param string $actionName
     * @param array  $params
     * @param string $returnedValue
     *
     * @return void
     * @throws ConsoleException
     * @throws RouterException
     * @throws EventsException
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testCliConsoleHandle(
        array $arguments,
        string $taskName,
        string $actionName,
        array $params,
        string $returnedValue
    ): void {
        $container = new DiFactoryDefault();

        $container->set(
            'data',
            function () {
                return 'data';
            }
        );

        $console = new CliConsole($container);

        $dispatcher = $console->getDI()
                              ->getShared('dispatcher')
        ;
        $dispatcher->setDefaultNamespace('Phalcon\Tests1\Fixtures\Tasks');

        $console->handle($arguments);

        $expected = $taskName;
        $actual   = $dispatcher->getTaskName();
        $this->assertSame($expected, $actual);

        $expected = $actionName;
        $actual   = $dispatcher->getActionName();
        $this->assertSame($expected, $actual);

        $expected = $params;
        $actual   = $dispatcher->getParams();
        $this->assertSame($expected, $actual);

        $expected = $returnedValue;
        $actual   = $dispatcher->getReturnedValue();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Cli\Console :: handle() - Issue #13724
     * Handling a BackendModule twice causes class already exists error #13724
     * <https://github.com/phalcon/cphalcon/issues/13724>
     *
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandle13724(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $dispatcher = $console->dispatcher;
        $dispatcher->setNamespaceName('Phalcon\Tests1\Modules\Backend\Tasks');

        $console->registerModules(
            [
                'backend' => [
                    'className' => BackendModule::class,
                    'path'      => self::dataDir('fixtures/modules/backend/Module.php'),
                ],
            ]
        );

        $console->handle(
            [
                'module' => 'backend',
                'action' => 'noop',
            ]
        );


        $console = new CliConsole(new DiFactoryDefault());

        $dispatcher = $console->dispatcher;
        $dispatcher->setNamespaceName('Phalcon\Tests1\Modules\Backend\Tasks');

        $console->registerModules(
            [
                'backend' => [
                    'className' => BackendModule::class,
                    'path'      => self::dataDir('fixtures/modules/backend/Module.php'),
                ],
            ]
        );

        $console->handle(
            [
                'module' => 'backend',
                'action' => 'noop',
            ]
        );

        $expected = 'main';
        $actual   = $dispatcher->getTaskName();
        $this->assertSame($expected, $actual);

        $expected = 'noop';
        $actual   = $dispatcher->getActionName();
        $this->assertSame($expected, $actual);

        $expected = 'backend';
        $actual   = $dispatcher->getModuleName();
        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandle787(): void
    {
        $console = new CliConsole(new DiFactoryDefault());
        $console->dispatcher->setDefaultNamespace('Phalcon\Tests1\Fixtures\Tasks');

        $console->handle(
            [
                'task'   => 'issue787',
                'action' => 'main',
            ]
        );

        $this->assertSame(
            'beforeExecuteRoute' . PHP_EOL . 'initialize' . PHP_EOL,
            Issue787Task::$output
        );
    }

    /**
     * Tests Phalcon\Cli\Console :: handle()
     *
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandleEventAfterHandleTask(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $eventsManager = $console->eventsManager;

        $eventsManager->attach(
            'console:afterHandleTask',
            function (Event $event, $console, $moduleObject) {
                throw new Exception('Console After Handle Task Event Fired');
            }
        );

        $console->registerModules(
            [
                'frontend' => [
                    'className' => FrontendModule::class,
                    'path'      => self::dataDir('fixtures/modules/frontend/Module.php'),
                ],
                'backend'  => [
                    'className' => BackendModule::class,
                    'path'      => self::dataDir('fixtures/modules/backend/Module.php'),
                ],
            ]
        );
        $console->dispatcher->setNamespaceName('Phalcon\Tests1\Modules\Backend\Tasks');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Console After Handle Task Event Fired'
        );

        $console->handle(
            [
                'module' => 'backend',
                'action' => 'noop',
            ]
        );
    }

    /**
     * Tests Phalcon\Cli\Console :: handle()
     *
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandleEventAfterStartModule(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $eventsManager = $console->eventsManager;

        $console->registerModules(
            [
                'frontend' => [
                    'className' => FrontendModule::class,
                    'path'      => self::dataDir('fixtures/modules/frontend/Module.php'),
                ],
                'backend'  => [
                    'className' => BackendModule::class,
                    'path'      => self::dataDir('fixtures/modules/backend/Module.php'),
                ],
            ]
        );

        $console->dispatcher->setNamespaceName('Phalcon\Tests1\Modules\Backend\Tasks');

        $eventsManager->attach(
            'console:afterStartModule',
            function (Event $event, $console, $moduleObject) {
                throw new Exception('Console After Start BackendModule Event Fired');
            }
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Console After Start BackendModule Event Fired'
        );

        $console->handle(
            [
                'module' => 'backend',
                'action' => 'noop',
            ]
        );
    }

    /**
     * Tests Phalcon\Cli\Console :: handle()
     *
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandleEventBeforeHandleTask(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $eventsManager = $console->eventsManager;

        $eventsManager->attach(
            'console:beforeHandleTask',
            function (Event $event, $console, $moduleObject) {
                throw new Exception('Console Before Handle Task Event Fired');
            }
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Console Before Handle Task Event Fired');

        $console->handle([]);
    }

    /**
     * Tests Phalcon\Cli\Console :: handle()
     *
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandleEventBeforeStartModule(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $eventsManager = $console->eventsManager;

        $eventsManager->attach(
            'console:beforeStartModule',
            function (Event $event, $console, $moduleName) {
                throw new Exception('Console Before Start BackendModule Event Fired');
            }
        );

        $console->registerModules(
            [
                'frontend' => [
                    'className' => FrontendModule::class,
                    'path'      => self::dataDir('fixtures/modules/frontend/Module.php'),
                ],
                'backend'  => [
                    'className' => BackendModule::class,
                ],
            ]
        );

        $console->dispatcher->setNamespaceName('Phalcon\Tests\Modules\Backend\Tasks');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Console Before Start BackendModule Event Fired'
        );

        $console->handle(
            [
                'module' => 'backend',
                'action' => 'noop',
            ]
        );
    }

    /**
     * Tests Phalcon\Cli\Console :: handle()
     *
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandleEventBoot(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $eventsManager = $console->eventsManager;

        $eventsManager->attach(
            'console:boot',
            function (Event $event, $console) {
                throw new Exception('Console Boot Event Fired');
            }
        );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Console Boot Event Fired');

        $console->handle();
    }

    /**
     * Tests Phalcon\Cli\Console :: handle() - BackendModules
     *
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandleModule(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $console->registerModules(
            [
                'frontend' => [
                    'className' => FrontendModule::class,
                    'path'      => self::dataDir('/fixtures/modules/frontend/Module.php'),
                ],
                'backend'  => [
                    'className' => BackendModule::class,
                    'path'      => self::dataDir('fixtures/modules/backend/Module.php'),
                ],
            ]
        );

        $dispatcher = $console->dispatcher;
        $dispatcher->setNamespaceName('Phalcon\Tests1\Modules\Backend\Tasks');

        $console->handle(
            [
                'module' => 'backend',
                'action' => 'noop',
            ]
        );

        $expected = 'main';
        $actual   = $dispatcher->getTaskName();
        $this->assertSame($expected, $actual);

        $expected = 'noop';
        $actual   = $dispatcher->getActionName();
        $this->assertSame($expected, $actual);

        $expected = 'backend';
        $actual   = $dispatcher->getModuleName();
        $this->assertSame($expected, $actual);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Task Run');

        $console->handle(
            [
                'module' => 'backend',
                'action' => 'throw',
            ]
        );
    }

    /**
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     *
     * @author Nathan Edwards <https://github.com/npfedwards>
     * @since  2019-01-06
     */
    public function testCliConsoleHandleModuleDoesNotExists(): void
    {
        $console = new CliConsole(new DiFactoryDefault());
        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage(
            "Module 'devtools' isn't registered in the console container"
        );

        // testing module
        $console->handle(
            [
                'module' => 'devtools',
                'task'   => 'main',
                'action' => 'hello',
                'World',
                '######',
            ]
        );
    }

    /**
     * @issue  16186
     * @return void
     */
    public function testCliConsoleHandleNoAction(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to check this under Windows');
        }

        $script = self::rootDir('tests/testbed/cli.php ');

        ob_start();
        $actual = shell_exec('sudo php ' . $script . 'print');
        ob_end_clean();

        $expected = 'printMainAction';
        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     * @throws ConsoleException
     * @throws EventsException
     * @throws RouterException
     */
    public function testCliConsoleHandleTaskDoesNotExists(): void
    {
        $console = new CliConsole(new DiFactoryDefault());

        $console->dispatcher->setDefaultNamespace('Dummy\\');

        $this->expectException(DispatcherException::class);
        $this->expectExceptionMessage(
            "Dummy\MainTask handler class cannot be loaded"
        );
        $this->expectExceptionCode(2);

        // testing namespace
        $console->handle(
            [
                'task'   => 'main',
                'action' => 'hello',
                'World',
                '!',
            ]
        );
    }
}
