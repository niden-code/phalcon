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
use Phalcon\Assets\Exception;
use Phalcon\Tests1\Fixtures\Assets\AssetExistsFixture;
use Phalcon\Tests1\Fixtures\Assets\AssetGetContentsFixture;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function dataDir2;
use function file_get_contents;

use const PHP_EOL;
use const PHP_OS_FAMILY;

final class GetContentTest extends AbstractUnitTestCase
{
    /**
     * @return string[][]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'type' => 'css',
                'path' => 'assets/assets/1198.css',
            ],
            [
                'type' => 'js',
                'path' => 'assets/assets/signup.js',
            ],
        ];
    }

    /**
     * Tests Phalcon\Assets\Asset\Css :: getContent()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssGetContent(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $asset = new Css('assets/assets/1198.css');

        $expected = file_get_contents(self::dataDir('assets/assets/1198.css'));
        $actual   = $asset->getContent(self::dataDir());
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getContent()
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
    public function testAssetsAssetGetContent(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $expected = file_get_contents(self::dataDir($path));
        $expected = str_replace("\r\n", PHP_EOL, $expected);
        $actual   = $asset->getContent(self::dataDir());
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Asset :: getContent() - exception 404
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetGetContentException404(): void
    {
        $file    = 'assets/assets/1198.css';
        $message = "Asset's content for '" . self::dataDir($file) . "' cannot be read";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($message);

        /** @var Asset $asset */
        $asset = new AssetExistsFixture('css', $file);

        $data = $asset->getContent(self::dataDir());
    }

    /**
     * Tests Phalcon\Assets\Asset :: getContent() - exception cannot read file
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetGetContentExceptionCannotReadFile(): void
    {
        $file    = 'assets/assets/1198.css';
        $message = "Asset's content for '" . self::dataDir($file) . "' cannot be read";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($message);

        $asset = new AssetGetContentsFixture('css', $file);

        $data = $asset->getContent(self::dataDir());
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: getContent()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetContent(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $asset = new Js('assets/assets/signup.js');

        $expected = file_get_contents(self::dataDir('assets/assets/signup.js'));
        $actual   = $asset->getContent(self::dataDir());
        $this->assertSame($expected, $actual);
    }
}
