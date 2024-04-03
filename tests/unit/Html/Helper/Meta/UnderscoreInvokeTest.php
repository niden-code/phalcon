<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Meta;

use Codeception\Example;
use Phalcon\Html\Escaper;
use Phalcon\Html\Exception;
use Phalcon\Html\Helper\Meta;
use Phalcon\Html\TagFactory;
use PHPUnit\Framework\TestCase;

use const PHP_EOL;

final class UnderscoreInvokeTest extends TestCase
{
    /**
     * Tests Phalcon\Html\Helper\Meta :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperMetaUnderscoreInvoke(
        string $indent,
        string $delimiter,
        array $addOptions,
        array $http,
        array $name,
        array $property,
        string $expected
    ): void {
        $escaper = new Escaper();
        $helper  = new Meta($escaper);

        $result = $helper($indent, $delimiter)
            ->add($addOptions)
            ->addHttp($http[0], $http[1])
            ->addName($name[0], $name[1])
            ->addProperty($property[0], $property[1])
        ;

        $actual   = (string)$result;
        $this->assertSame($expected, $actual);

        $factory = new TagFactory($escaper);
        $locator = $factory->newInstance('meta');
        $result  = $locator($indent, $delimiter)
            ->add($addOptions)
            ->addHttp($http[0], $http[1])
            ->addName($name[0], $name[1])
            ->addProperty($property[0], $property[1])
        ;

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
                    "charset" => 'utf-8',
                ],
                [
                    "X-UA-Compatible",
                    "IE=edge",
                ],
                [
                    "generator",
                    "Phalcon",
                ],
                [
                    "org:url",
                    "https://phalcon.io",
                ],
                "    <meta charset=\"utf-8\">" . PHP_EOL
                    . "    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">" . PHP_EOL
                    . "    <meta name=\"generator\" content=\"Phalcon\">" . PHP_EOL
                    . "    <meta property=\"org:url\" content=\"https://phalcon.io\">" . PHP_EOL,
            ],
            [
                '--',
                '+',
                [
                    "charset" => 'utf-8',
                ],
                [
                    "X-UA-Compatible",
                    "IE=edge",
                ],
                [
                    "generator",
                    "Phalcon",
                ],
                [
                    "org:url",
                    "https://phalcon.io",
                ],
                "--<meta charset=\"utf-8\">+"
                    . "--<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">+"
                    . "--<meta name=\"generator\" content=\"Phalcon\">+"
                    . "--<meta property=\"org:url\" content=\"https://phalcon.io\">+",
            ],
        ];
    }
}
