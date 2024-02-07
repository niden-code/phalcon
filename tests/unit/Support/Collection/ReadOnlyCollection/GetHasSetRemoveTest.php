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

use Phalcon\Support\Collection;
use Phalcon\Support\Collection\Exception;
use Phalcon\Support\Collection\ReadOnlyCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

final class GetHasSetRemoveTest extends TestCase
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
    public function testCollectionClearGetHast(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $actual = $collection->has('three');
        $this->assertTrue($actual);

        $actual = $collection->has('one');
        $this->assertTrue($actual);

        $actual = $collection->has('five');
        $this->assertTrue($actual);

        $expected = 3;
        $actual   = $collection->count();
        $this->assertSame($expected, $actual);

        $collection->clear();

        $expected = 0;
        $actual   = $collection->count();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Collection :: offsetUnset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionClearGetHasOffsetUnset(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The object is read only');
        $collection->offsetUnset('ten');
    }

    /**
     * Tests Phalcon\Support\Collection :: remove()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionClearGetHasRemove(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The object is read only');
        $collection->remove('ten');
    }

    /**
     * Tests Phalcon\Support\Collection :: unset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionClearGetHasUnset(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The object is read only');
        unset($collection->ten);
    }

    /**
     * Tests Phalcon\Support\Collection :: offsetSet()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionClearGetHasOffsetSet(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The object is read only');
        $collection->offsetSet('ten', 'eleven');
    }

    /**
     * Tests Phalcon\Support\Collection :: set()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionClearGetHasSet(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The object is read only');
        $collection->set('ten', 'eleven');
    }

    /**
     * Tests Phalcon\Support\Collection :: __set()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionClearGetHasUnderscoreSet(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new ReadOnlyCollection($data);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The object is read only');
        $collection->ten = 'eleven';
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
