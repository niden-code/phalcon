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

namespace Phalcon\Tests\Unit\Assets\Inline;

use Phalcon\Assets\Inline;
use Phalcon\Assets\Inline\Css;
use Phalcon\Assets\Inline\Js;
use PHPUnit\Framework\TestCase;

/**
 * Class GetSetAttributesTest extends TestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Inline
 */
final class GetSetAttributesTest extends TestCase
{
    /**
     * @return string[][]
     */
    public static function provider(): array
    {
        return [
            [
                'css',
                'p {color: #000099}',
            ],
            [
                'js',
                '<script>alert("Hello");</script>',
            ],
        ];
    }

    /**
     * Tests Phalcon\Assets\Inline\Css :: getAttributes()/setAttributes()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssGetSetAttributes(): void
    {
        $asset    = new Css('p {color: #000099}');
        $expected = [
            'data-key' => 'phalcon',
        ];

        $asset->setAttributes($expected);

        $this->assertSame(
            $expected,
            $asset->getAttributes()
        );
    }

    /**
     * Tests Phalcon\Assets\Inline :: getAttributes()/setAttributes()
     *
     * @dataProvider provider
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsInlineGetSetAttributes(
        string $type,
        string $content
    ): void {
        $asset    = new Inline($type, $content);
        $expected = [
            'data-key' => 'phalcon',
        ];

        $asset->setAttributes($expected);
        $actual = $asset->getAttributes();

        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Inline\Js :: getAttributes()/setAttributes()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineJsGetSetAttributes(): void
    {
        $asset    = new Js('<script>alert("Hello");</script>');
        $expected = [
            'data-key' => 'phalcon',
        ];

        $asset->setAttributes($expected);

        $this->assertSame(
            $expected,
            $asset->getAttributes()
        );
    }
}
