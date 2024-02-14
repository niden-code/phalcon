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

final class GetSetSourcePathTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: getSourcePath()/setSourcePath()
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
    public function testAssetsAssetCssGetSetSourcePath(
        string $path,
        bool $local
    ): void {
        $asset    = new Css($path, $local);
        $expected = '/phalcon/path';

        $asset->setSourcePath($expected);
        $actual = $asset->getSourcePath();

        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getSourcePath()/setSourcePath()
     *
     * @dataProvider providerPath
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
    public function testAssetsAssetGetSetSourcePath(
        string $type,
        string $path,
        bool $local
    ): void {
        $asset      = new Asset($type, $path, $local);
        $sourcePath = '/new/path';

        $asset->setSourcePath($sourcePath);
        $actual = $asset->getSourcePath();

        $this->assertSame($sourcePath, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getSourcePath()/setSourcePath()
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
    public function testAssetsAssetJsGetSetSourcePath(
        string $path,
        bool $local
    ): void {
        $asset    = new Js($path, $local);
        $expected = '/phalcon/path';

        $asset->setSourcePath($expected);
        $actual = $asset->getSourcePath();

        $this->assertSame($expected, $actual);
    }
}
