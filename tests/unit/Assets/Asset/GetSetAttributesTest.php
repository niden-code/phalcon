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

final class GetSetAttributesTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset\Css :: setAttributes()
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
    public function testAssetsAssetCssSetAttributes(
        string $path,
        bool $local
    ): void {
        $asset      = new Css($path, $local);
        $attributes = [
            'data-key' => 'phalcon',
        ];

        $asset->setAttributes($attributes);
        $actual = $asset->getAttributes();
        $this->assertSame($attributes, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getAttributes()/setAttributes()
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
    public function testAssetsAssetGetSetAttributes(
        string $type,
        string $path
    ): void {
        $asset      = new Asset($type, $path);
        $attributes = [
            'data-key' => 'phalcon',
        ];

        $asset->setAttributes($attributes);
        $actual = $asset->getAttributes();
        $this->assertSame($attributes, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: setAttributes()
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
    public function testAssetsAssetJsSetAttributes(
        string $path,
        bool $local
    ): void {
        $asset      = new Js($path, $local);
        $attributes = [
            'data-key' => 'phalcon',
        ];

        $asset->setAttributes($attributes);
        $actual = $asset->getAttributes();
        $this->assertSame($attributes, $actual);
    }
}
