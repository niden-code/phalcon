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
use Phalcon\Image\Enum;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function safeDeleteFile2;

#[RequiresPhpExtension('imagick')]
final class FlipTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: flip()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickFlip(): void
    {
        $image = new Imagick(
            self::dataDir('assets/images/example-jpg.jpg')
        );

        $image->setResourceLimit(6, 1);

        // Flip the image from top to bottom
        $outputFile = self::outputDir('image/imagick/flip.jpg');
        $image->flip(Enum::HORIZONTAL)
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
