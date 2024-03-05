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

namespace Phalcon\Tests\Unit\Image\Adapter\Imagick;

use Phalcon\Image\Adapter\Imagick;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
final class CropTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: crop()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickCrop(): void
    {
        $image = new Imagick(
            self::dataDir('assets/images/example-jpg.jpg')
        );

        $image->setResourceLimit(6, 1);

        // Crop the image to 200x200 pixels, from the center
        $outputFile = self::outputDir('image/imagick/crop.jpg');
        $image->crop(200, 200)
              ->save($outputFile)
        ;

        $this->assertFileExists($outputFile);

        $expected = 200;
        $actual   = $image->getWidth();
        $this->assertSame($expected, $actual);

        $expected = 200;
        $actual   = $image->getHeight();
        $this->assertSame($expected, $actual);

        $this->safeDeleteFile($outputFile);
    }
}
