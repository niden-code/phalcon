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

namespace Phalcon\Tests\Unit\Session\Bag;

use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Session\Bag;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;

final class InitTest extends AbstractUnitTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Session\Bag :: init()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionBagInit(): void
    {
        $this->setNewFactoryDefault();
        $this->setDiService('sessionStream');
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new Bag(
            $this->container->get("session"),
            'BagTest'
        );

        $expected = 0;
        $actual   = $collection->count();
        $this->assertEquals($expected, $actual);

        $collection->init($data);
        $actual = $collection->toArray();
        $this->assertEquals($data, $actual);
    }
}
