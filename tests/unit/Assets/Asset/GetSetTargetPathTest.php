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

final class GetSetTargetPathTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: getTargetPath()/setTargetPath()
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
    public function testAssetsAssetCssGetSetTargetPath(
        string $path,
        bool $local
    ): void {
        $asset = new Css($path, $local);

        $targetPath = '/phalcon/path';
        $asset->setTargetPath($targetPath);
        $actual = $asset->getTargetPath();

        $this->assertSame($targetPath, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getTargetPath()/setTargetPath()
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
    public function testAssetsAssetJsGetSetTargetPath(
        string $path,
        bool $local
    ): void {
        $asset = new Js($path, $local);

        $targetPath = '/phalcon/path';
        $asset->setTargetPath($targetPath);
        $actual = $asset->getTargetPath();

        $this->assertSame($targetPath, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getTargetPath()/setTargetPath()
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
    public function testAssetsAssetSetTargetPathLocal(
        string $type,
        string $path,
        bool $local
    ): void {
        $asset      = new Asset($type, $path, $local);
        $targetPath = '/new/path';

        $asset->setTargetPath($targetPath);

        $actual = $asset->getTargetPath();
        $this->assertSame($targetPath, $actual);
    }
}
