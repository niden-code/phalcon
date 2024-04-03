<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Script;

use Codeception\Example;
use Phalcon\Html\Escaper;
use Phalcon\Html\Exception;
use Phalcon\Html\Helper\Script;
use Phalcon\Html\TagFactory;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

final class UnderscoreInvokeTest extends TestCase
{
    /**
     * Tests Phalcon\Html\Helper\Script :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperScriptUnderscoreInvoke(
        string $indent,
        string $delimiter,
        array $addOptions,
        string $expected
    ): void {
        $escaper = new Escaper();
        $helper  = new Script($escaper);

        $result = $helper($indent, $delimiter);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1]);
        }

        $actual   = (string)$result;
        $this->assertSame($expected, $actual);

        $factory = new TagFactory($escaper);
        $locator = $factory->newInstance('script');

        $result = $locator($indent, $delimiter);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1]);
        }

        $actual = (string)$result;
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        return [
            [
                '    ',
                PHP_EOL,
                [
                    [
                        "/js/custom.js",
                        [],
                    ],
                    [
                        "/js/print.js",
                        ["ie" => "active"],
                    ],
                ],
                "    <script type=\"application/javascript\" "
                    . "src=\"/js/custom.js\"></script>" . PHP_EOL
                    . "    <script type=\"application/javascript\" "
                    . "src=\"/js/print.js\" ie=\"active\"></script>" . PHP_EOL,
            ],
            [
                '--',
                '+',
                [
                    [
                        "/js/custom.js",
                        [],
                    ],
                    [
                        "/js/print.js",
                        ["ie" => "active"],
                    ],
                ],
                "--<script type=\"application/javascript\" "
                    . "src=\"/js/custom.js\"></script>+"
                    . "--<script type=\"application/javascript\" "
                    . "src=\"/js/print.js\" ie=\"active\"></script>+",
            ],
        ];
    }
}
