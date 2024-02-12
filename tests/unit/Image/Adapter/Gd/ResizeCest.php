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

use Codeception\Example;
use Phalcon\Image\Adapter\Gd;
use Phalcon\Image\Enum;
use Phalcon\Image\Exception;
use Phalcon\Tests\Fixtures\Traits\GdTrait;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use PHPUnit\Framework\TestCase;

use function dataDir;
use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class ResizeTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: resize()
     *
     * @dataProvider providerExamples
     *
     * @param string $file
     * @param string $source
     * @param int    $width
     * @param int    $height
     * @param string $hash
     *
     * @return void
     *
     * @throws Exception
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdResize(
        string $file,
        string $source,
        int $width,
        int $height,
        string $hash
    ): void {
        $this->checkJpegSupport($this);

        $outputDir = 'tests/image/gd';
        $output    = outputDir($outputDir . '/' . $file);

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

        safeDeleteFile2($output);
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: resize()
     *
     * @dataProvider providerExceptions
     *
     * @param string $master
     * @param int    $width
     * @param int    $height
     * @param string $message
     *
     * @return void
     *
     * @throws Exception
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdResizeExceptions(
        int $master,
        int $width,
        int $height,
        string $message
    ): void {
        $this->checkJpegSupport($this);

        $source = dataDir2('assets/images/example-jpg.jpg');
        $image  = new Gd($source);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($message);

        $image->resize($width, $height, $master);
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'source' => dataDir2('assets/images/example-jpg.jpg'),
                'file'   => 'resize.jpg',
                'height' => 75,
                'width'  => 197,
                'hash'   => 'fbf9f3e3c3c1c183',
            ],
            [
                'source' => dataDir2('assets/images/example-png.png'),
                'file'   => 'resize.jpg',
                'height' => 50,
                'width'  => 50,
                'hash'   => 'bf9f8fc5bf9bc0d0',
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
                'master'  => Enum::TENSILE,
                'height'  => null,
                'width'   => 199,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::TENSILE,
                'height'  => 199,
                'width'   => null,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::AUTO,
                'height'  => null,
                'width'   => 199,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::AUTO,
                'height'  => 199,
                'width'   => null,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::INVERSE,
                'height'  => null,
                'width'   => 199,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::INVERSE,
                'height'  => 199,
                'width'   => null,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::PRECISE,
                'height'  => null,
                'width'   => 199,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::PRECISE,
                'height'  => 199,
                'width'   => null,
                'message' => 'width and height must be specified',
            ],
            [
                'master'  => Enum::WIDTH,
                'height'  => 199,
                'width'   => null,
                'message' => 'width must be specified',
            ],
            [
                'master'  => Enum::HEIGHT,
                'height'  => null,
                'width'   => 199,
                'message' => 'height must be specified',
            ],
        ];
    }
}
