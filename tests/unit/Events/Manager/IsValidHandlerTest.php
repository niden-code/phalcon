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

use Phalcon\Events\Manager;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class IsValidHandlerTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Events\Manager :: isValidHandler()
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testEventsManagerIsValidHandler(): void
    {
        $objectHandler  = new Manager();
        $closureHandler = function () {
            return true;
        };

        $dataset = [
            [
                false,
                'handler',
            ],
            [
                false,
                134,
            ],
            [
                true,
                $objectHandler,
            ],
            [
                true,
                [$objectHandler, 'hasListeners'],
            ],
            [
                true,
                $closureHandler,
            ],
        ];

        foreach ($dataset as $data) {
            $manager = new Manager();

            $expected = $data[0];
            $handler  = $data[1];
            $actual   = $manager->isValidHandler($handler);
            $this->assertSame($expected, $actual);
        }
    }
}
