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
     * Tests Phalcon\Support\Registry ::
     * get()/__get()/offsetGet()/has()/__isset()/offsetExists()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistryClearGetHasSetRemove(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $registry = new Registry($data);

        $actual = $registry->has('three');
        $this->assertTrue($actual);

        $actual = $registry->has('one');
        $this->assertTrue($actual);

        $actual = $registry->has('five');
        $this->assertTrue($actual);

        $registry->clear();

        $registry->set('ten', 'eleven');
        $registry->twelve = 'thirteen';
        $registry->offsetSet('fourteen', 'fifteen');

        $actual = $registry->has('ten');
        $this->assertTrue($actual);

        $actual = isset($registry->ten);
        $this->assertTrue($actual);

        $actual = $registry->offsetExists('ten');
        $this->assertTrue($actual);

        $expected = 'eleven';
        $actual   = $registry->get('ten');
        $this->assertSame($expected, $actual);

        $expected = 'eleven';
        $actual   = $registry->ten;
        $this->assertSame($expected, $actual);

        $expected = 'eleven';
        $actual   = $registry->offsetGet('ten');
        $this->assertSame($expected, $actual);

        $expected = 3;
        $actual   = $registry->count();
        $this->assertSame($expected, $actual);

        $registry->remove('ten');

        $expected = 2;
        $actual   = $registry->count();
        $this->assertSame($expected, $actual);

        $actual = $registry->has('ten');
        $this->assertFalse($actual);

        unset($registry->twelve);

        $expected = 1;
        $actual   = $registry->count();
        $this->assertSame($expected, $actual);

        $actual = isset($registry->ten);
        $this->assertFalse($actual);

        $registry->offsetUnset('fourteen');

        $expected = 0;
        $actual   = $registry->count();
        $this->assertSame($expected, $actual);

        $actual = $registry->offsetExists('fourteen');
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Support\Registry :: get() - cast
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
    public function testRegistryGetCast(
        string $cast,
        mixed $value,
        mixed $expected
    ): void {
        $collection = new Registry(
            [
                'value' => $value,
            ]
        );

        $actual = $collection->get('value', null, $cast);
        $this->assertEquals($expected, $actual);
    }
}
