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
final class TextTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: text()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickText(): void
    {
        $image = new Imagick(
            self::dataDir('assets/images/example-jpg.jpg')
        );

        $image->setResourceLimit(6, 1);

        $image->text(
            'Phalcon',
            10,
            10,
            100,
            '000099',
            12,
            self::dataDir('assets/fonts/Roboto-Thin.ttf')
        )
              ->save(self::outputDir('image/imagick/text.jpg'))
        ;

        $outputFile = self::outputDir('image/imagick/')
            . 'text.jpg';

        $this->assertFileExists($outputFile);

        $expected = 1820;
        $actual   = $image->getWidth();
        $this->assertSame($expected, $actual);

        $expected = 694;
        $actual   = $image->getHeight();
        $this->assertSame($expected, $actual);

        $this->safeDeleteFile($outputFile);
    }
}
