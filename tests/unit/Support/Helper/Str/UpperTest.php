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

namespace Phalcon\Tests\Unit\Support\Helper\Str;

use Phalcon\Support\Helper\Str\Upper;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class UpperTest extends AbstractUnitTestCase
{
    /**
     * @return string[][]
     */
    public static function providerBasic(): array
    {
        return [
            [
                'text'     => 'hello',
                'expected' => 'HELLO',
            ],

            [
                'text'     => 'HELLO',
                'expected' => 'HELLO',
            ],

            [
                'text'     => '1234',
                'expected' => '1234',
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function providerMultibyte(): array
    {
        return [
            [
                'text'     => 'ПРИВЕТ МИР!',
                'expected' => 'ПРИВЕТ МИР!',
            ],

            [
                'text'     => 'ПриВЕт Мир!',
                'expected' => 'ПРИВЕТ МИР!',
            ],

            [
                'text'     => 'привет мир!',
                'expected' => 'ПРИВЕТ МИР!',
            ],

            [
                'text'     => 'MÄNNER',
                'expected' => 'MÄNNER',
            ],

            [
                'text'     => 'mÄnnER',
                'expected' => 'MÄNNER',
            ],

            [
                'text'     => 'männer',
                'expected' => 'MÄNNER',
            ],
        ];
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: upper()
     *
     * @dataProvider providerBasic
     *
     * @param string $text
     * @param string $expected
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrUpper(
        string $text,
        string $expected
    ): void {
        $object = new Upper();

        $actual = $object($text);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: upper() - multi-bytes encoding
     *
     * @dataProvider providerMultibyte
     *
     * @param string $text
     * @param string $expected
     *
     * @return void
     *
     * @author       Stanislav Kiryukhin <korsar.zn@gmail.com>
     * @since        2015-05-06
     */
    public function testSupportHelperStrUpperMultiBytesEncoding(
        string $text,
        string $expected
    ): void {
        $object = new Upper();

        $actual = $object($text);
        $this->assertSame($expected, $actual);
    }
}
