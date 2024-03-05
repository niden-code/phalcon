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
final class BackgroundTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: background()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickBackground(): void
    {
        $image = new Imagick(
            self::dataDir('assets/images/example-jpg.jpg')
        );

        $image->setResourceLimit(6, 1);

        // Add a watermark to the bottom right of the image
        $outputFile = self::outputDir('image/imagick/background.jpg');
        $image->background('#000')
              ->save($outputFile)
        ;

        $this->assertFileExists($outputFile);

        $expected = 200;
        $actual   = $image->getWidth();
        $this->assertGreaterThan($expected, $actual);

        $expected = 200;
        $actual   = $image->getHeight();
        $this->assertGreaterThan($expected, $actual);

        $this->safeDeleteFile($outputFile);
    }
}
