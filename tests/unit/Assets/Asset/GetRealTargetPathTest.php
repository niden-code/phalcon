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

use Codeception\Stub;
use Phalcon\Assets\Asset;
use Phalcon\Assets\Asset\Css;
use Phalcon\Assets\Asset\Js;

use function dataDir2;

final class GetRealTargetPathTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: getRealTargetPath()
     *
     * @dataProvider providerCssExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetCssGetAssetKeyLocal(string $path): void
    {
        $asset = new Css($path);

        $expected = $path;
        $actual   = $asset->getRealTargetPath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getRealTargetPath() - css local
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
    public function testAssetsAssetGetRealTargetPath(
        string $type,
        string $path,
        bool $local
    ): void {
        $asset = new Asset($type, $path, $local);

        $expected = $path;
        $actual   = $asset->getRealTargetPath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getRealTargetPath() - css local 404
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetGetRealTargetPath404(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $file = 'assets/assets/1198.css';
        /** @var Asset $asset */
        $asset = Stub::construct(
            Asset::class,
            [
                'css',
                $file,
            ],
            [
                'phpFileExists' => true,
            ]
        );

        $expected = dataDir2($file);
        $actual   = $asset->getRealTargetPath(dataDir2());
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getRealTargetPath()
     *
     * @dataProvider providerJsExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetJsGetAssetKeyLocal(string $path): void
    {
        $asset = new Js($path);

        $expected = $path;
        $actual   = $asset->getRealTargetPath();
        $this->assertSame($expected, $actual);
    }
}
