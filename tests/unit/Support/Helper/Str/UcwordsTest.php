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

use Phalcon\Support\Helper\Str\Ucwords;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class UcwordsTest extends AbstractUnitTestCase
{
    /**
     * @return string[][]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'text'     => 'hello goodbye',
                'expected' => 'Hello Goodbye',
            ],

            [
                'text'     => 'HELLO GOODBYE',
                'expected' => 'Hello Goodbye',
            ],

            [
                'text'     => '1234',
                'expected' => '1234',
            ],
            [
                'text'     => 'ПРИВЕТ МИР!',
                'expected' => 'Привет Мир!',
            ],

            [
                'text'     => 'ПриВЕт Мир!',
                'expected' => 'Привет Мир!',
            ],

            [
                'text'     => 'привет мир!',
                'expected' => 'Привет Мир!',
            ],

            [
                'text'     => 'MÄNNER MÄNNER',
                'expected' => 'Männer Männer',
            ],

            [
                'text'     => 'mÄnnER mÄnnER',
                'expected' => 'Männer Männer',
            ],

            [
                'text'     => 'männer männer',
                'expected' => 'Männer Männer',
            ],
        ];
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: ucwords()
     *
     * @dataProvider providerExamples
     *
     * @param string $text
     * @param string $expected
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrUcwords(
        string $text,
        string $expected
    ): void {
        $object = new Ucwords();

        $actual = $object($text);

        $this->assertSame($expected, $actual);
    }
}
