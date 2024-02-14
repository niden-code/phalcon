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

final class IsSetIsLocalTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: isLocal()/setIsLocal()
     *
     * @dataProvider providerCssExamples
     *
     * @param string $path
     * @param bool   $local
     * @param bool   $newLocal
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetCssSetLocal(
        string $path,
        bool $local,
        bool $newLocal
    ): void {
        $asset = new Css($path, $local);

        $asset->setIsLocal($newLocal);
        $expected = $newLocal;
        $actual   = $asset->isLocal();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: isLocal()/setIsLocal()
     *
     * @dataProvider providerJsExamples
     *
     * @param string $path
     * @param bool   $local
     * @param bool   $newLocal
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetJsSetLocal(
        string $path,
        bool $local,
        bool $newLocal
    ): void {
        $asset = new Js($path, $local);

        $asset->setIsLocal($newLocal);
        $expected = $newLocal;
        $actual   = $asset->isLocal();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: isLocal()/setIsLocal()
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
    public function testAssetsAssetSetLocalCssLocal(
        string $type,
        string $path,
        bool $local
    ): void {
        $asset = new Asset($type, $path);

        $actual = $asset->isLocal();
        $this->assertTrue($actual);

        $asset->setIsLocal($local);

        $actual = $asset->isLocal();
        $this->assertSame($local, $actual);
    }
}
