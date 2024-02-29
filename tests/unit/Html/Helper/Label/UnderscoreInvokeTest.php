<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Label;

use Codeception\Example;
use Phalcon\Html\Escaper;
use Phalcon\Html\Exception;
use Phalcon\Html\Helper\Label;
use Phalcon\Html\TagFactory;
use PHPUnit\Framework\TestCase;

use function uniqid;

final class UnderscoreInvokeTest extends TestCase
{
    /**
     * Tests Phalcon\Html\Helper\Label :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperLabelUnderscoreInvoke(
        string $expected,
        string $text,
        array $attributes,
        bool $raw
    ): void {
        $escaper = new Escaper();
        $helper  = new Label($escaper);

        $actual   = $helper($text, $attributes, $raw);
        $this->assertSame($expected, $actual);

        $factory  = new TagFactory($escaper);
        $locator  = $factory->newInstance('label');

        $actual   = $locator($text, $attributes, $raw);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        $text = uniqid();
        return [
            [
                '<label></label>',
                '',
                [],
                false,
            ],
            [
                '<label for="my-name" id="my-id">' . $text . '&amp;' . $text . '</label>',
                $text . '&' . $text,
                [
                    'id'  => 'my-id',
                    'for' => 'my-name',
                ],
                false,
            ],
            [
                '<label for="my-name" id="my-id" class="my-class">' . $text . '&amp;' . $text . '</label>',
                $text . '&' . $text,
                [
                    'class' => 'my-class',
                    'for'   => 'my-name',
                    'id'    => 'my-id',
                ],
                false,
            ],
            [
                '<label for="my-name" id="my-id">raw & text</label>',
                'raw & text',
                [
                    'id'  => 'my-id',
                    'for' => 'my-name',
                ],
                true,
            ],
        ];
    }
}
