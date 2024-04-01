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

namespace Phalcon\Tests\Unit\Di\Service;

use Codeception\Example;
use Phalcon\Di\Service;
use Phalcon\Html\Escaper;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class IsSharedTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Di\Service :: isShared()
     *
     * @param Example $example
     *
     * @return void
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-09-09
     */
    public function testDiServiceIsShared(): void
    {
        $data = [
            [
                new Service(Escaper::class),
                false,
            ],
            [
                new Service(Escaper::class, true),
                true,
            ],
            [
                new Service(Escaper::class, false),
                false,
            ],
        ];

        foreach ($data as $example) {
            $service  = $example[0];
            $expected = $example[1];
            $actual   = $service->isShared();
            $this->assertSame($expected, $actual);
        }
    }
}
