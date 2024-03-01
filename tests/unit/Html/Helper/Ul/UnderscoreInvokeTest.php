<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Ul;

use Phalcon\Html\Escaper;
use Phalcon\Html\Helper\Ul;
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
                "<ul id=\"carsList\">
    <li class=\"active\">&gt; Ferrari</li>
    <li>&gt; Ford</li>
    <li>&gt; Dodge</li>
    <li>&gt; Toyota</li>
</ul>",
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
                "<ul id=\"carsList\">
    <li class=\"active\">&gt; Ferrari</li>
    <li>&gt; Ford</li>
    <li>&gt; Dodge</li>
    <li>&gt; Toyota</li>
</ul>",
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
                "<ul id=\"carsList\">+--<li class=\"active\">&gt; Ferrari</li>+"
                . "--<li>&gt; Ford</li>+--<li>&gt; Dodge</li>+--<li>&gt; Toyota</li>+</ul>",
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
                "<ul id=\"carsList\">
    <li class=\"active\">> Ferrari</li>
    <li>> Ford</li>
    <li>> Dodge</li>
    <li>> Toyota</li>
</ul>",
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
                "<ul id=\"carsList\">
    <li class=\"active\">> Ferrari</li>
    <li>> Ford</li>
    <li>> Dodge</li>
    <li>> Toyota</li>
</ul>",
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
                "<ul id=\"carsList\">+--<li class=\"active\">> Ferrari</li>+"
                . "--<li>> Ford</li>+--<li>> Dodge</li>+--<li>> Toyota</li>+</ul>",
            ],
        ];
    }

    /**
     * Tests Phalcon\Html\Helper\Ul :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperUlUnderscoreInvoke(
        string $indent,
        string $delimiter,
        array $attributes,
        array $addOptions,
        string $expected
    ): void {
        $escaper = new Escaper();
        $helper  = new Ul($escaper);

        $result = $helper($indent, $delimiter, $attributes);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1], $add[2]);
        }

        $actual = (string)$result;
        $this->assertSame($expected, $actual);

        $factory = new TagFactory($escaper);
        $locator = $factory->newInstance('ul');

        $result = $locator($indent, $delimiter, $attributes);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1], $add[2]);
        }

        $actual = (string)$result;
        $this->assertSame($expected, $actual);
    }
}
