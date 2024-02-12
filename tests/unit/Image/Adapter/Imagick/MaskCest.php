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
use Phalcon\Tests\Fixtures\Traits\ImagickTrait;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

use function dataDir;
use function outputDir;
use function safeDeleteFile2;

#[RequiresPhpExtension('imagick')]
final class MaskTest extends TestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: mask()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickMask(): void
    {
        $image = new Imagick(
            dataDir2('assets/images/example-jpg.jpg')
        );

        $image->setResourceLimit(6, 1);

        $mask = new Imagick(
            dataDir2('assets/images/example-png.png')
        );

        // Add a watermark to the bottom right of the image
        $outputFile = outputDir('tests/image/imagick/mask.jpg');
        $image->mask($mask)
              ->save($outputFile)
        ;

        $this->assertFileExists($outputFile);

        $expected = 200;
        $actual   = $image->getWidth();
        $this->assertGreaterThan($expected, $actual);

        $expected = 200;
        $actual   = $image->getHeight();
        $this->assertGreaterThan($expected, $actual);

        safeDeleteFile2($outputFile);
    }
}
