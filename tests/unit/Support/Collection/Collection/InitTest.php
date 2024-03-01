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

namespace Phalcon\Tests\Unit\Support\Collection\Collection;

use Phalcon\Support\Collection;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class InitTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Support\Collection :: init()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionInit(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new Collection();

        $this->assertSame(
            0,
            $collection->count()
        );

        $collection->init($data);

        $expected = $data;
        $actual   = $collection->toArray();
        $this->assertSame($expected, $actual);
    }
}
