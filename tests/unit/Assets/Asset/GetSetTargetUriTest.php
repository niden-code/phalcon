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

final class GetSetTargetUriTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: getTargetUri()/setTargetUri()
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
    public function testAssetsAssetCssGetSetTargetUri(
        string $path,
        bool $local
    ): void {
        $asset    = new Css($path, $local);
        $expected = '/phalcon/path';
        $asset->setTargetUri($expected);

        $actual = $asset->getTargetUri();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getTargetUri()/setTargetUri()
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
    public function testAssetsAssetJsGetSetTargetUri(
        string $path,
        bool $local
    ): void {
        $asset    = new Js($path, $local);
        $expected = '/phalcon/path';
        $asset->setTargetUri($expected);

        $actual = $asset->getTargetUri();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: setTargetUri() - local
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
    public function testAssetsAssetSetTargetUriJsLocal(
        string $type,
        string $path,
        bool $local
    ): void {
        $asset     = new Asset($type, $path, $local);
        $targetUri = '/new/path';

        $asset->setTargetUri($targetUri);
        $actual = $asset->getTargetUri();
        $this->assertSame($targetUri, $actual);
    }
}
