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
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function safeDeleteFile2;

#[RequiresPhpExtension('imagick')]
final class LiquidRescaleTest extends AbstractUnitTestCase
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
            self::dataDir('assets/images/example-jpg.jpg')
        );

        $image->setResourceLimit(6, 1);

        // Resize to 200 pixels on the shortest side
        $outputFile = self::outputDir('image/imagick/liquidRescale.jpg');
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

        $this->safeDeleteFile($outputFile);
    }
}
