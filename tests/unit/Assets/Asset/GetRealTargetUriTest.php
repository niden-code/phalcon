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

namespace Phalcon\Tests\Unit\Assets\Asset;

use Phalcon\Assets\Asset;
use Phalcon\Assets\Asset\Css;
use Phalcon\Assets\Asset\Js;

final class GetRealTargetUriTest extends AbstractAssetCase
{
    /**
     * @return string[][]
     */
    public static function providerTargetUri(): array
    {
        return [
            [
                'css',
                'css/docs.css',
                true,
                '',
                'css/docs.css',
            ],
            [
                'js',
                'js/jquery.js',
                true,
                '',
                'js/jquery.js',
            ],
            [
                'css',
                'https://phalcon.ld/css/docs.css',
                false,
                '',
                'https://phalcon.ld/css/docs.css',
            ],
            [
                'js',
                'https://phalcon.ld/js/jquery.js',
                false,
                '',
                'https://phalcon.ld/js/jquery.js',
            ],
            [
                'css',
                'css/assets.css',
                true,
                'css/docs.css',
                'css/docs.css',
            ],
        ];
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: getRealTargetUri()
     *
     * @dataProvider providerCssExamples
     *
     * @param string $path
     * @param bool   $local
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetCssGetRealTargetUri(
        string $path,
        bool $local
    ): void {
        $asset = new Css($path, $local);

        $expected = $path;
        $actual   = $asset->getRealTargetUri();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getRealTargetUri() - local
     *
     * @dataProvider providerTargetUri
     *
     * @param string $type
     * @param string $path
     * @param bool   $local
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetGetRealTargetUri(
        string $type,
        string $path,
        bool $local,
        string $targetUri,
        string $expected
    ): void {
        $asset = new Asset($type, $path, $local);

        $asset->setTargetUri($targetUri);

        $actual = $asset->getRealTargetUri();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getRealTargetUri()
     *
     * @dataProvider providerJsExamples
     *
     * @param string $path
     * @param bool   $local
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetJsGetRealTargetUri(
        string $path,
        bool $local
    ): void {
        $asset = new Js($path, $local);

        $expected = $path;
        $actual   = $asset->getRealTargetUri();
        $this->assertSame($expected, $actual);
    }
}
