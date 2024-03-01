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
use stdClass;

final class GetHasSetRemoveTest extends AbstractUnitTestCase
{
    /**
     * @return array
     */
    public static function providerGet(): array
    {
        $sample      = new stdClass();
        $sample->one = 'two';

        return [
            [
                'boolean',
                1,
                true,
            ],
            [
                'bool',
                1,
                true,
            ],
            [
                'integer',
                "123",
                123,
            ],
            [
                'int',
                "123",
                123,
            ],
            [
                'float',
                "123.45",
                123.45,
            ],
            [
                'double',
                "123.45",
                123.45,
            ],
            [
                'string',
                123,
                "123",
            ],
            [
                'array',
                $sample,
                ['one' => 'two'],
            ],
            [
                'object',
                ['one' => 'two'],
                $sample,
            ],
            [
                'null',
                1234,
                null,
            ],
        ];
    }

    /**
     * Tests Phalcon\Support\Collection ::
     * get()/__get()/offsetGet()/has()/__isset()/offsetExists()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionClearGetHasSetRemove(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new Collection($data);

        $actual = $collection->has('three');
        $this->assertTrue($actual);

        $actual = $collection->has('one');
        $this->assertTrue($actual);

        $actual = $collection->has('five');
        $this->assertTrue($actual);

        $collection->clear();

        /**
         * Element not set -> default
         */
        $expected = 'default';
        $actual   = $collection->get('unknown', 'default');
        $this->assertSame($expected, $actual);

        /**
         * Value set - key null -> default
         */
        $collection->set('ten', null);
        $expected = 'default';
        $actual   = $collection->get('ten', 'default');
        $this->assertSame($expected, $actual);

        $collection->set('ten', 'eleven');
        $collection->twelve = 'thirteen';
        $collection->offsetSet('fourteen', 'fifteen');

        $actual = $collection->has('ten');
        $this->assertTrue($actual);

        $actual = isset($collection->ten);
        $this->assertTrue($actual);

        $actual = $collection->offsetExists('ten');
        $this->assertTrue($actual);

        $expected = 'eleven';
        $actual   = $collection->get('ten');
        $this->assertSame($expected, $actual);

        $expected = 'eleven';
        $actual   = $collection->ten;
        $this->assertSame($expected, $actual);

        $expected = 'eleven';
        $actual   = $collection->offsetGet('ten');
        $this->assertSame($expected, $actual);

        $expected = 3;
        $actual   = $collection->count();
        $this->assertSame($expected, $actual);

        $collection->remove('ten');

        $expected = 2;
        $actual   = $collection->count();
        $this->assertSame($expected, $actual);

        $actual = $collection->has('ten');
        $this->assertFalse($actual);

        unset($collection->twelve);

        $expected = 1;
        $actual   = $collection->count();
        $this->assertSame($expected, $actual);

        $actual = isset($collection->ten);
        $this->assertFalse($actual);

        $collection->offsetUnset('fourteen');

        $expected = 0;
        $actual   = $collection->count();
        $this->assertSame($expected, $actual);

        $actual = $collection->offsetExists('fourteen');
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Support\Collection :: get() - cast
     *
     * @dataProvider providerGet
     *
     * @param int  $value
     * @param bool $expected
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-10-12
     */
    public function testCollectionGetCast(
        string $cast,
        mixed $value,
        mixed $expected
    ): void {
        $collection = new Collection(
            [
                'value' => $value,
            ]
        );

        $actual = $collection->get('value', null, $cast);
        $this->assertEquals($expected, $actual);
    }
}
