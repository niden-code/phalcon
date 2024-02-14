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

final class ConstructTest extends AbstractAssetCase
{
    /**
     * Tests Phalcon\Assets\Asset :: __construct() - attributes
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
    public function testAssetsAssetConstructAttributes(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $expected = [];
        $actual   = $asset->getAttributes();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: __construct() - attributes set
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
    public function testAssetsAssetConstructAttributesSet(
        string $type,
        string $path
    ): void {
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Asset(
            $type,
            $path,
            true,
            true,
            $attributes
        );

        $actual = $asset->getAttributes();
        $this->assertSame($attributes, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: __construct() - filter
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
    public function testAssetsAssetConstructFilter(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $actual = $asset->getFilter();
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: __construct() - filter set
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
    public function testAssetsAssetConstructFilterSet(
        string $type,
        string $path
    ): void {
        $asset = new Asset(
            $type,
            $path,
            true,
            false
        );

        $actual = $asset->getFilter();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: __construct() - local
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
    public function testAssetsAssetConstructLocal(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $actual = $asset->isLocal();
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: __construct() - remote
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
    public function testAssetsAssetConstructRemote(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path, false);

        $actual = $asset->isLocal();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: __construct() - attributes
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssConstructAttributes(): void
    {
        $asset = new Css('css/docs.css');

        $expected = [];
        $actual   = $asset->getAttributes();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: __construct() - attributes set
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssConstructAttributesSet(): void
    {
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Css(
            'css/docs.css',
            true,
            true,
            $attributes
        );

        $actual = $asset->getAttributes();
        $this->assertSame($attributes, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: __construct() - filter
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssConstructFilter(): void
    {
        $asset  = new Css('css/docs.css');
        $actual = $asset->getFilter();
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: __construct() - filter set
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssConstructFilterSet(): void
    {
        $asset  = new Css('css/docs.css', true, false);
        $actual = $asset->getFilter();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: __construct() - local
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
    public function testAssetsAssetCssConstructLocal(
        string $path,
        bool $local
    ): void {
        $asset = new Css($path, $local);

        $expected = $local;
        $actual   = $asset->isLocal();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: __construct() - attributes
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsConstructAttributes(): void
    {
        $asset = new Js('js/jquery.js');

        $expected = [];
        $actual   = $asset->getAttributes();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: __construct() - attributes set
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsConstructAttributesSet(): void
    {
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Js(
            'js/jquery.js',
            true,
            true,
            $attributes
        );

        $expected = $attributes;
        $actual   = $asset->getAttributes();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: __construct() - filter
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsConstructFilter(): void
    {
        $asset = new Js('js/jquery.js');

        $actual = $asset->getFilter();
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: __construct() - filter set
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsConstructFilterSet(): void
    {
        $asset = new Js('js/jquery.js', true, false);

        $actual = $asset->getFilter();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: __construct() - local
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
    public function testAssetsAssetJsConstructLocal(
        string $path,
        bool $local
    ): void {
        $asset = new Js($path, $local);

        $expected = $local;
        $actual   = $asset->isLocal();

        $this->assertSame($expected, $actual);
    }
}
