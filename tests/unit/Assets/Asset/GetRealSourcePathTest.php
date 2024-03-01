<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Assets\Asset;

use Phalcon\Assets\Asset;
use Phalcon\Assets\Asset\Css;
use Phalcon\Assets\Asset\Js;

use function dataDir2;

use const PHP_OS_FAMILY;

final class GetRealSourcePathTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: getRealSourcePath() - css local
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssGetRealSourcePathLocal(): void
    {
        $asset  = new Css('css/docs.css');
        $actual = $asset->getRealSourcePath();
        $this->assertEmpty($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: getRealSourcePath() - remote
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssGetRealSourcePathRemote(): void
    {
        $path  = 'https://phalcon.ld/css/docs.css';
        $asset = new Css($path, false);

        $actual = $asset->getRealSourcePath();
        $this->assertSame($path, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getRealSourcePath() - css/js local
     *
     * @dataProvider providerLocal
     *
     * @param string $type
     * @param string $path
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetGetRealSourcePathLocal(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $actual = $asset->getRealSourcePath();
        $this->assertEmpty($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getRealSourcePath() - css/js remote
     *
     * @dataProvider providerRemote
     *
     * @param string $type
     * @param string $path
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetGetRealSourcePathRemote(
        string $type,
        string $path
    ): void {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $asset = new Asset($type, $path, false);

        $expected = $path;
        $actual   = $asset->getRealSourcePath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getRealSourcePath() - js local
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetRealSourcePathLocal(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $file  = self::dataDir('assets/assets/jquery.js');
        $asset = new Js($file);

        $expected = $file;
        $actual   = $asset->getRealSourcePath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getRealSourcePath() - js local -
     * does not exist
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetRealSourcePathLocalDoesNotExist(): void
    {
        $asset  = new Js('js/jquery.js');
        $actual = $asset->getRealSourcePath();
        $this->assertEmpty($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getRealSourcePath() - remote
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetRealSourcePathRemote(): void
    {
        $path  = 'https://phalcon.ld/js/jquery.js';
        $asset = new Js($path, false);

        $expected = $path;
        $actual   = $asset->getRealSourcePath();
        $this->assertSame($expected, $actual);
    }
}
