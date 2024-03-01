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

namespace Phalcon\Tests\Unit\Support\Registry;

use Phalcon\Support\Registry;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class InitTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Support\Registry :: init()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistryInit(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $registry = new Registry();

        $this->assertSame(
            0,
            $registry->count()
        );

        $registry->init($data);

        $expected = $data;
        $actual   = $registry->toArray();
        $this->assertSame($expected, $actual);
    }
}
