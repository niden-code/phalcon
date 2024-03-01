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

namespace Phalcon\Tests\Unit\Support\Collection\ReadOnlyCollection;

use Phalcon\Support\Collection\ReadOnlyCollection;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function json_encode;

use const JSON_PRETTY_PRINT;

final class IteratorJsonArrayTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Support\Collection :: getIterator()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionGetIterator(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        foreach ($collection as $key => $value) {
            $expected = $data[$key];
            $actual   = $collection[$key];
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Support\Collection :: toArray()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionToArray(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $expected = $data;
        $actual   = $collection->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Collection :: toJson()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionToJson(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $expected = json_encode($data);
        $actual   = $collection->toJson();
        $this->assertSame($expected, $actual);

        $expected = json_encode($data, JSON_PRETTY_PRINT);
        $actual   = $collection->toJson(JSON_PRETTY_PRINT);
        $this->assertSame($expected, $actual);
    }
}
