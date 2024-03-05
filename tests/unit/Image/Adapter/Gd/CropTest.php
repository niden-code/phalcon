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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;

#[RequiresPhpExtension('gd')]
final class CropTest extends AbstractUnitTestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: crop()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testImageAdapterGdCropJpg()
    {
        $this->checkJpegSupport($this);

        $image = new Gd(self::dataDir('assets/images/example-jpg.jpg'));

        $outputDir = 'image/gd';
        $width     = 200;
        $height    = 200;
        $cropImage = 'crop.jpg';
        $output    = self::outputDir($outputDir . '/' . $cropImage);
        $hash      = 'ffffffb803402030';

        // Resize to 200 pixels on the shortest side
        $image->crop($width, $height)
              ->save(self::outputDir($outputDir . '/' . $cropImage))
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
     * Tests Phalcon\Image\Adapter\Gd :: crop()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testImageAdapterGdCropJpgWithOffset(): void
    {
        $this->checkJpegSupport($this);

        $image = new Gd(self::dataDir('assets/images/example-jpg.jpg'));

        $outputDir = 'image/gd';
        $width     = 200;
        $height    = 200;
        $offsetX   = 200;
        $offsetY   = 200;
        $cropImage = 'cropwithoffset.jpg';
        $output    = self::outputDir($outputDir . '/' . $cropImage);
        $hash      = 'fffff00000000000';

        // Resize to 200 pixels on the shortest side
        $image->crop($width, $height, $offsetX, $offsetY)
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
}
