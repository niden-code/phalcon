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
use Phalcon\Tests\Fixtures\Traits\GdTrait;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use PHPUnit\Framework\TestCase;

use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class PixelateTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: pixelate()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testImageAdapterGdPixelate(): void
    {
        $this->checkJpegSupport($this);

        $params = [
            [7, 'fbf9f7e3c3c18183'],
            [21, 'fbf9f7e3c1c3c183'],
            [35, 'fbf9f3e3c3c18183'],
            [60, 'fbfbf3e3c3c3c383'],
        ];
        foreach ($params as [$amount, $hash]) {
            $image = new Gd(dataDir2('assets/images/example-jpg.jpg'));

            $outputDir   = 'tests/image/gd';
            $outputImage = $amount . '-pixelate.jpg';
            $output      = outputDir($outputDir . '/' . $outputImage);

            $image->pixelate($amount)
                  ->save($output)
            ;

            $this->assertFileExists($outputImage);

            $actual = $this->checkImageHash($output, $hash);
            $this->assertTrue($actual);

            safeDeleteFile2($outputImage);
        }
    }
}
