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

use Phalcon\Support\Helper\Str\Friendly;
use PHPUnit\Framework\TestCase;

final class FriendlyTest extends TestCase
{
    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        return [
            [
                'This is a Test',
                '-',
                true,
                [],
                'this-is-a-test',
            ],
            [
                'This is a Test',
                '_',
                true,
                [],
                'this_is_a_test',
            ],
            [
                'This is a Test',
                '-',
                false,
                [],
                'This-is-a-Test',
            ],
            [
                'This is a Test',
                '_',
                true,
                'i',
                'th_s_s_a_test',
            ],
            [
                'This is a Test',
                '_',
                true,
                ['i', 'h'],
                't_s_s_a_test',
            ],
            [
                "Mess'd up --text-- just (to) stress /test/ ?our! "
                . '`little` \\clean\\ url fun.ction!?-->',
                '-',
                true,
                [],
                'messd-up-text-just-to-stress-test-our-little-clean-url-function',
            ],
            [
                "Perché l'erba è verde?",
                '-',
                true,
                "'",
                'perche-l-erba-e-verde',
            ],
            [
                "Perché l'erba è verde?",
                '_',
                false,
                ['e', 'a'],
                'P_rch_l_rb_v_rd',
            ],
        ];
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: friendly()
     *
     * @dataProvider providerExamples
     *
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrFriendly(
        string $text,
        string $separator,
        bool $lowercase,
        array | string $replace,
        string $expected
    ): void {
        $object = new Friendly();

        $actual = $object->__invoke(
            $text,
            $separator,
            $lowercase,
            $replace
        );

        $this->assertSame($expected, $actual);
    }
}
