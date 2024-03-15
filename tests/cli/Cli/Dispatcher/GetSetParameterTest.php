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

namespace Phalcon\Tests\Cli\Cli\Dispatcher;

use Phalcon\Cli\Dispatcher;
use Phalcon\Tests\Support\AbstractCliTestCase;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;

class GetSetParameterTest extends AbstractCliTestCase
{
    use DiTrait2;

    public function testCliDispatcherGetSetParameter(): void
    {
        $this->setNewCliFactoryDefault();

        $dispatcher = new Dispatcher();

        $this->container->setShared('dispatcher', $dispatcher);
        $dispatcher->setDI($this->container);

        // Test $this->dispatcher->getParam()
        $dispatcher->setNamespaceName('Phalcon\Tests\Fixtures\Tasks');
        $dispatcher->setTaskName('params');
        $dispatcher->setActionName('param');

        $dispatcher->setParams(
            ['This', 'Is', 'An', 'Example']
        );

        $dispatcher->dispatch();

        $expected = '$param[0] is the same as $this->dispatcher->getParam(0)';
        $actual   = $dispatcher->getReturnedValue();
        $this->assertSame($expected, $actual);
    }
}
