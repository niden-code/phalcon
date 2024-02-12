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
use Phalcon\Tests\Fixtures\Traits\GdTrait;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use PHPUnit\Framework\TestCase;

use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class WatermarkTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: watermark()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function imageAdapterGdWatermarkJpgInsideJpg(): void
    {
        $this->checkJpegSupport($this);

        $image = new Gd(
            dataDir2('assets/images/example-jpg.jpg')
        );

        $watermark = new Gd(
            dataDir2('assets/images/example-jpg.jpg')
        );
        $watermark->resize(250, null, Enum::WIDTH);

        $outputDir   = 'tests/image/gd';
        $outputImage = 'watermark.jpg';
        $output      = outputDir($outputDir . '/' . $outputImage);
        $offsetX     = 200;
        $offsetY     = 200;
        $opacity     = 50;

        $hash = 'fbf9f3e3c3c18183';

        // Resize to 200 pixels on the shortest side
        $image->watermark($watermark, $offsetX, $offsetY, $opacity)
              ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        safeDeleteFile2($output);
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: watermark()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function imageAdapterGdWatermarkPngInsideJpg(): void
    {
        $this->checkJpegSupport($this);

        $image = new Gd(
            dataDir2('assets/images/example-jpg.jpg')
        );

        $watermark = new Gd(
            dataDir2('assets/images/example-png.png')
        );

        $outputDir   = 'tests/image/gd';
        $outputImage = 'watermark.jpg';
        $output      = outputDir($outputDir . '/' . $outputImage);
        $offsetX     = 200;
        $offsetY     = 200;

        $hash = 'fbf9f3e3c3c18183';

        $image->watermark($watermark, $offsetX, $offsetY)
              ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        safeDeleteFile2($output);
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: watermark()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function imageAdapterGdWatermarkJpgInsidePng(): void
    {
        $this->checkJpegSupport($this);

        $image = new Gd(
            dataDir2('assets/images/example-png.png')
        );

        $watermark = new Gd(
            dataDir2('assets/images/example-jpg.jpg')
        );
        $watermark->resize(50, 50, Enum::NONE);

        $outputDir   = 'tests/image/gd';
        $outputImage = 'watermark.png';
        $output      = outputDir($outputDir . '/' . $outputImage);
        $offsetX     = 10;
        $offsetY     = 10;
        $opacity     = 50;

        $hash = '107c7c7c7e1c1818';

        // Resize to 200 pixels on the shortest side
        $image->watermark($watermark, $offsetX, $offsetY, $opacity)
              ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        safeDeleteFile2($output);
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: watermark()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function imageAdapterGdWatermarkPngInsidePng(): void
    {
        $image = new Gd(
            dataDir2('assets/images/example-png.png')
        );

        $watermark = new Gd(
            dataDir2('assets/images/example-png.png')
        );
        $watermark->resize(null, 30, Enum::HEIGHT);

        $outputDir   = 'tests/image/gd';
        $outputImage = 'watermark.png';
        $output      = outputDir($outputDir . '/' . $outputImage);
        $offsetX     = 20;
        $offsetY     = 20;
        $opacity     = 75;

        $hash = '10787c3c3e181818';

        $image->watermark($watermark, $offsetX, $offsetY, $opacity)
              ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        safeDeleteFile2($output);
    }
}
