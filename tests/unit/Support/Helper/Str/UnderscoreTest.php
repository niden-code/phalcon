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

use Phalcon\Support\Helper\Str\Underscore;
use PHPUnit\Framework\TestCase;

final class UnderscoreTest extends TestCase
{
    /**
     * @return string[][]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'start a horse',
                'start_a_horse',
            ],
            [
                "five\tcats",
                'five_cats',
            ],
            [
                ' look behind ',
                'look_behind',
            ],
            [
                " \t Awesome \t  \t Phalcon ",
                'Awesome_Phalcon',
            ],
        ];
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: underscore()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrUnderscore(
        string $source,
        string $expected
    ): void {
        $object = new Underscore();

        $actual = $object($source);
        $this->assertSame($expected, $actual);
    }
}
