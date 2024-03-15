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

use Phalcon\Tests\Support\AbstractCliTestCase;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;

class GetSetEventsManagerTest extends AbstractCliTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Cli\Dispatcher :: getEventsManager()/setEventsManager()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliDispatcherGetSetEventsManager(): void
    {
        $console = $this->newService('console');
        $manager = $this->newService('eventsManager');

        $console->setEventsManager($manager);

        $actual = $console->getEventsManager();
        $this->assertSame($manager, $actual);
    }
}
