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
use Phalcon\Tests\Unit\Assets\Asset\Css\AbstractCssCase;

final class GetSetTypeTest extends AbstractAssetCase
{
    /**
     * @return string[][]
     */
    public static function providerType(): array
    {
        return [
            [
                'type'    => 'css',
                'path'    => 'css/docs.css',
                'newType' => 'js',
            ],
            [
                'type'    => 'css',
                'path'    => 'js/jquery.js',
                'newType' => 'js',
            ],
        ];
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: getType()/setType()
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
    public function testAssetsAssetCssGetSetType(
        string $path,
        bool $local
    ): void {
        $asset = new Css($path, $local);

        $expected = 'css';
        $actual   = $asset->getType();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getType()/setType()
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
    public function testAssetsAssetJsGetSetType(
        string $path,
        bool $local
    ): void {
        $asset = new Js($path, $local);

        $expected = 'js';
        $actual   = $asset->getType();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getType()/setType()
     *
     * @dataProvider providerType
     *
     * @param string $type
     * @param string $path
     * @param string $newType
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsAssetSetType(
        string $type,
        string $path,
        string $newType
    ): void {
        $asset = new Asset($type, $path);

        $asset->setType($newType);
        $expected = $newType;
        $actual   = $asset->getType();
        $this->assertSame($expected, $actual);
    }
}
