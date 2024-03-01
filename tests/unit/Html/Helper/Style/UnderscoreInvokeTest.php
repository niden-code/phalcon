<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Style;

use Phalcon\Html\Escaper;
use Phalcon\Html\Helper\Style;
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
                false,
                [
                    [
                        "custom.css",
                        [],
                    ],
                    [
                        "print.css",
                        ["media" => "print"],
                    ],
                ],
                "    <link rel=\"stylesheet\" type=\"text/css\" "
                . "href=\"custom.css\" media=\"screen\" />" . PHP_EOL
                . "    <link rel=\"stylesheet\" type=\"text/css\" "
                . "href=\"print.css\" media=\"print\" />" . PHP_EOL,
            ],
            [
                '--',
                '+',
                false,
                [
                    [
                        "custom.css",
                        [],
                    ],
                    [
                        "print.css",
                        ["media" => "print"],
                    ],
                ],
                "--<link rel=\"stylesheet\" type=\"text/css\" "
                . "href=\"custom.css\" media=\"screen\" />+"
                . "--<link rel=\"stylesheet\" type=\"text/css\" "
                . "href=\"print.css\" media=\"print\" />+",
            ],
            [
                '    ',
                PHP_EOL,
                true,
                [
                    [
                        "custom.css",
                        [],
                    ],
                    [
                        "print.css",
                        ["media" => "print"],
                    ],
                ],
                "    <style type=\"text/css\" "
                . "href=\"custom.css\" media=\"screen\" />" . PHP_EOL
                . "    <style type=\"text/css\" "
                . "href=\"print.css\" media=\"print\" />" . PHP_EOL,
            ],
            [
                '--',
                '+',
                true,
                [
                    [
                        "custom.css",
                        [],
                    ],
                    [
                        "print.css",
                        ["media" => "print"],
                    ],
                ],
                "--<style type=\"text/css\" "
                . "href=\"custom.css\" media=\"screen\" />+"
                . "--<style type=\"text/css\" "
                . "href=\"print.css\" media=\"print\" />+",
            ],
        ];
    }

    /**
     * Tests Phalcon\Html\Helper\Style :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperStyleUnderscoreInvoke(
        string $indent,
        string $delimiter,
        bool $style,
        array $addOptions,
        string $expected
    ): void {
        $escaper = new Escaper();
        $helper  = new Style($escaper);

        $result = $helper($indent, $delimiter);
        $result->setStyle($style);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1]);
        }

        $actual = (string)$result;
        $this->assertSame($expected, $actual);

        /**
         * Try the TagFactory
         */
        $factory = new TagFactory($escaper);
        $locator = $factory->newInstance('style');

        $result = $locator($indent, $delimiter);
        $result->setStyle($style);
        foreach ($addOptions as $add) {
            $result->add($add[0], $add[1]);
        }

        $actual = (string)$result;
        $this->assertSame($expected, $actual);
    }
}
