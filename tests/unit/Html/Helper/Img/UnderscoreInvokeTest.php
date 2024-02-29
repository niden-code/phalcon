<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Img;

use Codeception\Example;
use Phalcon\Html\Escaper;
use Phalcon\Html\Exception;
use Phalcon\Html\Helper\Img;
use Phalcon\Html\TagFactory;
use PHPUnit\Framework\TestCase;

final class UnderscoreInvokeTest extends TestCase
{
    /**
     * Tests Phalcon\Html\Helper\Img :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperImgUnderscoreInvoke(
        string $expected,
        array $attributes
    ): void {
        $escaper = new Escaper();
        $helper  = new Img($escaper);

        $actual   = $helper('/my-url', $attributes);
        $this->assertSame($expected, $actual);

        $factory  = new TagFactory($escaper);
        $locator  = $factory->newInstance('img');

        $actual   = $locator('/my-url', $attributes);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        return [
            [
                '<img src="/my-url" />',
                [],
            ],
            [
                '<img src="/my-url" id="my-id" name="my-name" />',
                [
                    'id'   => 'my-id',
                    'name' => 'my-name',
                ],
            ],
            [
                '<img src="/my-url" id="my-id" name="my-name" class="my-class" />',
                [
                    'src'   => '/other-url',
                    'class' => 'my-class',
                    'name'  => 'my-name',
                    'id'    => 'my-id',
                ],
            ],
        ];
    }
}
