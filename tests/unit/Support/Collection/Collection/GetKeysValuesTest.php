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

final class GetKeysValuesTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Support\Collection :: getKeys()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionGetKeys(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'FIVE'  => 'six',
        ];

        $collection = new Collection($data);

        $expected = [
            0 => 'one',
            1 => 'three',
            2 => 'five',
        ];
        $actual   = $collection->getKeys();
        $this->assertSame($expected, $actual);

        $expected = [
            0 => 'one',
            1 => 'three',
            2 => 'FIVE',
        ];
        $actual   = $collection->getKeys(false);
        $this->assertSame($expected, $actual);

        /**
         * Sensitive
         */
        $collection = new Collection($data, false);

        $expected = [
            0 => 'one',
            1 => 'three',
            2 => 'FIVE',
        ];
        $actual   = $collection->getKeys();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Collection :: getValues()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionGetValues(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'FIVE'  => 'SIX',
        ];

        $collection = new Collection($data);

        $expected = [
            0 => 'two',
            1 => 'four',
            2 => 'SIX',
        ];
        $actual   = $collection->getValues();
        $this->assertSame($expected, $actual);
    }
}
