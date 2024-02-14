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

use function hash;

final class GetAssetKeyTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: getAssetKey()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssGetAssetKey(): void
    {
        $path = 'css/docs.css';

        $asset    = new Css($path);
        $assetKey = hash("sha256", 'css:' . $path);
        $actual   = $asset->getAssetKey();

        $this->assertSame($assetKey, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getAssetKey()
     *
     * @dataProvider providerExamples
     *
     * @param string $type
     * @param string $path
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetGetAssetKey(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $assetKey = hash("sha256", $type . ':' . $path);
        $actual   = $asset->getAssetKey();
        $this->assertSame($assetKey, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getAssetKey()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetAssetKey(): void
    {
        $path     = 'js/jquery.js';
        $asset    = new Js($path);
        $expected = hash("sha256", 'js:' . $path);
        $actual   = $asset->getAssetKey();

        $this->assertSame($expected, $actual);
    }
}
