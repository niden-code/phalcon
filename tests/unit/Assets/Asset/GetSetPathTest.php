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

use function uniqid;

final class GetSetPathTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: getPath()/setPath()
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
    public function testAssetsAssetCssGetSetPath(
        string $path,
        bool $local
    ): void {
        $asset = new Css($path, $local);

        $expected = $path;
        $actual   = $asset->getPath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getPath()/setPath()
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
    public function testAssetsAssetGetSetPath(
        string $type,
        string $path
    ): void {
        $path1 = uniqid('/');
        $asset = new Asset($type, $path1);

        $expected = $path1;
        $actual   = $asset->getPath();
        $this->assertSame($expected, $actual);

        $expected = $path;
        $asset->setPath($path);
        $actual = $asset->getPath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getPath()/setPath()
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
    public function testAssetsAssetJsGetSetPath(
        string $path,
        bool $local
    ): void {
        $asset = new Js($path, $local);

        $expected = $path;
        $actual   = $asset->getPath();
        $this->assertSame($expected, $actual);
    }
}
