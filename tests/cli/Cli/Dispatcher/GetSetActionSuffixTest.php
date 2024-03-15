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

class GetSetActionSuffixTest extends AbstractCliTestCase
{
    /**
     * Tests Phalcon\Cli\Dispatcher :: getActionSuffix()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliDispatcherGetActionSuffix(): void
    {
        $dispatcher = new Dispatcher();
        $dispatcher->setActionSuffix('Task');

        $expected = 'Task';
        $actual   = $dispatcher->getActionSuffix();
        $this->assertSame($expected, $actual);
    }
}
