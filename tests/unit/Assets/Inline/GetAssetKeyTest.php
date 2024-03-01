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
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function hash;

final class GetAssetKeyTest extends AbstractUnitTestCase
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
     * Tests Phalcon\Assets\Inline\Css :: getAssetKey()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssGetAssetKey(): void
    {
        $content = 'p {color: #000099}';
        $asset   = new Css($content);

        $expected = hash("sha256", 'css:' . $content);
        $actual   = $asset->getAssetKey();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Inline :: getAssetKey()
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     *
     * @dataProvider provider
     */
    public function testAssetsInlineGetAssetKey(
        string $type,
        string $content
    ): void {
        $asset = new Inline(
            $type,
            $content
        );

        $expected = hash(
            "sha256",
            $type . ':' . $content
        );

        $actual = $asset->getAssetKey();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Inline\Js :: getAssetKey()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineJsGetAssetKey(): void
    {
        $content = '<script>alert("Hello");</script>';
        $asset   = new Js($content);

        $expected = hash("sha256", 'js:' . $content);
        $actual   = $asset->getAssetKey();
        $this->assertSame($expected, $actual);
    }
}
