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

namespace Phalcon\Tests\Unit\Image\Adapter\Gd;

use Phalcon\Image\Adapter\Gd;
use Phalcon\Image\Enum;
use Phalcon\Image\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;

#[RequiresPhpExtension('gd')]
final class ResizeTest extends AbstractUnitTestCase
{
    use GdTrait2;

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                self::dataDir('assets/images/example-jpg.jpg'),
                'resize.jpg',
                75,
                197,
                'fbf9f3e3c3c1c183',
            ],
            [
                self::dataDir('assets/images/example-png.png'),
                'resize.jpg',
                50,
                50,
                'bf9f8fc5bf9bc0d0',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function providerExceptions(): array
    {
        return [
            [
                Enum::TENSILE,
                null,
                199,
                'width and height must be specified',
            ],
            [
                Enum::TENSILE,
                199,
                null,
                'width and height must be specified',
            ],
            [
                Enum::AUTO,
                null,
                199,
                'width and height must be specified',
            ],
            [
                Enum::AUTO,
                199,
                null,
                'width and height must be specified',
            ],
            [
                Enum::INVERSE,
                null,
                199,
                'width and height must be specified',
            ],
            [
                Enum::INVERSE,
                199,
                null,
                'width and height must be specified',
            ],
            [
                Enum::PRECISE,
                null,
                199,
                'width and height must be specified',
            ],
            [
                Enum::PRECISE,
                199,
                null,
                'width and height must be specified',
            ],
            [
                Enum::WIDTH,
                199,
                null,
                'width must be specified',
            ],
            [
                Enum::HEIGHT,
                null,
                199,
                'height must be specified',
            ],
        ];
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: resize()
     *
     * @dataProvider providerExamples
     *
     * @param string $file
     * @param string $source
     * @param int    $height
     * @param int    $width
     * @param string $hash
     *
     * @return void
     *
     * @throws Exception
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdResize(
        string $source,
        string $file,
        int $height,
        int $width,
        string $hash
    ): void {
        $this->checkJpegSupport($this);

        $outputDir = 'image/gd';
        $output    = self::outputDir($outputDir . '/' . $file);

        $image = new Gd($source);

        $image->resize($width, $height)
              ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $image->getWidth();
        $this->assertSame($width, $actual);

        $actual = $image->getHeight();
        $this->assertSame($height, $actual);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        $this->safeDeleteFile($output);
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: resize()
     *
     * @dataProvider providerExceptions
     *
     * @param int      $master
     * @param int|null $width
     * @param int|null $height
     * @param string   $message
     *
     * @return void
     *
     * @throws Exception
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdResizeExceptions(
        int $master,
        ?int $height,
        ?int $width,
        string $message
    ): void {
        $this->checkJpegSupport($this);

        $source = self::dataDir('assets/images/example-jpg.jpg');
        $image  = new Gd($source);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($message);

        $image->resize($width, $height, $master);
    }
}
