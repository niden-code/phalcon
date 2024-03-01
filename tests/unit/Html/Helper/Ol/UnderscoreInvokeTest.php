<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Ol;

use Phalcon\Html\Escaper;
use Phalcon\Html\Helper\Ol;
use Phalcon\Html\TagFactory;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use const PHP_EOL;

final class UnderscoreInvokeTest extends AbstractUnitTestCase
{
    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        return [
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        false,
                    ],
                    [
                        "> Ford",
                        [],
                        false,
                    ],
                    [
                        "> Dodge",
                        [],
                        false,
                    ],
                    [
                        "> Toyota",
                        [],
                        false,
                    ],
                ],
                "<ol id=\"carsList\">
    <li class=\"active\">&gt; Ferrari</li>
    <li>&gt; Ford</li>
    <li>&gt; Dodge</li>
    <li>&gt; Toyota</li>
</ol>",
            ],
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        false,
                    ],
                    [
                        "> Ford",
                        [],
                        false,
                    ],
                    [
                        "> Dodge",
                        [],
                        false,
                    ],
                    [
                        "> Toyota",
                        [],
                        false,
                    ],
                ],
                "<ol id=\"carsList\">
    <li class=\"active\">&gt; Ferrari</li>
    <li>&gt; Ford</li>
    <li>&gt; Dodge</li>
    <li>&gt; Toyota</li>
</ol>",
            ],
            [
                '--',
                '+',
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        false,
                    ],
                    [
                        "> Ford",
                        [],
                        false,
                    ],
                    [
                        "> Dodge",
                        [],
                        false,
                    ],
                    [
                        "> Toyota",
                        [],
                        false,
                    ],
                ],
                "<ol id=\"carsList\">+--<li class=\"active\">&gt; Ferrari</li>+"
                . "--<li>&gt; Ford</li>+--<li>&gt; Dodge</li>+--<li>&gt; Toyota</li>+</ol>",
            ],
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        true,
                    ],
                    [
                        "> Ford",
                        [],
                        true,
                    ],
                    [
                        "> Dodge",
                        [],
                        true,
                    ],
                    [
                        "> Toyota",
                        [],
                        true,
                    ],
                ],
                "<ol id=\"carsList\">
    <li class=\"active\">> Ferrari</li>
    <li>> Ford</li>
    <li>> Dodge</li>
    <li>> Toyota</li>
</ol>",
            ],
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        true,
                    ],
                    [
                        "> Ford",
                        [],
                        true,
                    ],
                    [
                        "> Dodge",
                        [],
                        true,
                    ],
                    [
                        "> Toyota",
                        [],
                        true,
                    ],
                ],
                "<ol id=\"carsList\">
    <li class=\"active\">> Ferrari</li>
    <li>> Ford</li>
    <li>> Dodge</li>
    <li>> Toyota</li>
</ol>",
            ],
            [
                '--',
                '+',
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        true,
                    ],
                    [
                        "> Ford",
                        [],
                        true,
                    ],
                    [
                        "> Dodge",
                        [],
                        true,
                    ],
                    [
                        "> Toyota",
                        [],
                        true,
                    ],
                ],
                "<ol id=\"carsList\">+--<li class=\"active\">> Ferrari</li>+"
                . "--<li>> Ford</li>+--<li>> Dodge</li>+--<li>> Toyota</li>+</ol>",
            ],
        ];
    }

    /**
     * Tests Phalcon\Html\Helper\Ol :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperOlUnderscoreInvoke(
        string $indent,
        string $delimiter,
        array $attributes,
        array $addOptions,
        string $expected
    ): void {
        $escaper = new Escaper();
        $helper  = new Ol($escaper);

        $result = $helper($indent, $delimiter, $attributes);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1], $add[2]);
        }

        $actual = (string)$result;
        $this->assertSame($expected, $actual);

        $factory = new TagFactory($escaper);
        $locator = $factory->newInstance('ol');

        $result = $locator($indent, $delimiter, $attributes);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1], $add[2]);
        }

        $actual = (string)$result;
        $this->assertSame($expected, $actual);
    }
}
