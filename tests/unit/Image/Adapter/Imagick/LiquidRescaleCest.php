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
final class LiquidRescaleTest extends TestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: liquidRescale()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickLiquidRescale(): void
    {
        $this->markTestSkipped('Check library support');

        $image = new Imagick(
            dataDir2('assets/images/example-jpg.jpg')
        );

        $image->setResourceLimit(6, 1);

        // Resize to 200 pixels on the shortest side
        $outputFile = outputDir('tests/image/imagick/liquidRescale.jpg');
        $image->liquidRescale(200, 200)
              ->save($outputFile)
        ;

        $this->assertFileExists($outputFile);

        $expected = 200;
        $actual   = $image->getWidth();
        $this->assertSame($expected, $actual);

        $expected = 200;
        $actual   = $image->getHeight();
        $this->assertSame($expected, $actual);

        safeDeleteFile2($outputFile);
    }
}
