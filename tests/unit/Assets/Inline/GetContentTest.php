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
 * Class GetContentTest extends TestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Inline
 */
final class GetContentTest extends TestCase
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
     * Tests Phalcon\Assets\Inline\Css :: getContent()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssGetContent(): void
    {
        $content = 'p {color: #000099}';
        $asset   = new Css($content);

        $actual = $asset->getContent();
        $this->assertSame($content, $actual);
    }

    /**
     * Tests Phalcon\Assets\Inline :: getContent()
     *
     * @dataProvider provider
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsInlineGetContent(
        string $type,
        string $content
    ): void {
        $asset = new Inline($type, $content);

        $expected = $content;
        $actual   = $asset->getContent();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Inline\Js :: getContent()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineJsGetContent(): void
    {
        $content = '<script>alert("Hello");</script>';
        $asset   = new Js($content);

        $actual = $asset->getContent();
        $this->assertSame($content, $actual);
    }
}
