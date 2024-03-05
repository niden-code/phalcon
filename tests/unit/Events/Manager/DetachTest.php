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

namespace Phalcon\Tests\Unit\Events\Manager;

use Codeception\Example;
use Phalcon\Events\Exception;
use Phalcon\Events\Manager;
use stdClass;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class DetachTest extends AbstractUnitTestCase
{
    /**
     * Tests detach handler by using an Object
     *
     * @dataProvider providerBoolean
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     * @issue        12882
     */
    public function testEventsManagerDetach(bool $enablePriorities): void
    {
        $eventType = 'test:detachable';
        $manager   = new Manager();
        $manager->enablePriorities($enablePriorities);

        $handlerOne = function () {
            echo __METHOD__;
        };
        $handlerTwo = new stdClass();
        $manager->attach($eventType, $handlerOne);

        $events = $this->getProtectedProperty($manager, 'events');

        $this->assertCount(1, $events);
        $this->assertArrayHasKey($eventType, $events);
        $this->assertCount(1, $events[$eventType]);

        $manager->detach($eventType, $handlerTwo);
        $events = $this->getProtectedProperty($manager, 'events');

        $this->assertCount(1, $events);
        $this->assertArrayHasKey($eventType, $events);
        $this->assertCount(1, $events[$eventType]);

        $manager->detach($eventType, $handlerOne);
        $events = $this->getProtectedProperty($manager, 'events');

        $this->assertCount(1, $events);
        $this->assertArrayHasKey($eventType, $events);
        $this->assertCount(0, $events[$eventType]);
    }

    /**
     * Tests detach handler by using an Object - exception
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testEventsManagerDetachException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Event handler must be an Object or Callable');

        $manager = new Manager();
        $manager->detach('test:detachable', false);
    }

    /**
     * @return array
     */
    public static function providerBoolean(): array
    {
        return [
            [true],
            [false],
        ];
    }
}
