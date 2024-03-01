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
use Phalcon\Image\Exception;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class RotateTest extends AbstractUnitTestCase
{
    use GdTrait2;

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'jpg',
                0,
                'fbf9f3e3c3c18183',
            ],
            [
                'jpg',
                45,
                '60f0f83c1c0f0f06',
            ],
            [
                'jpg',
                90,
                'ff3f0f0703009dff',
            ],
            [
                'jpg',
                180,
                'c18183c3c7cf9fdf',
            ],
            [
                'jpg',
                270,
                'ffb900c0e0f0fcff',
            ],
            [
                'png',
                0,
                '30787c3c1e181818',
            ],
            [
                'png',
                45,
                '001c1c1c7c3c0000',
            ],
            [
                'png',
                90,
                '00060ffffe1c1000',
            ],
            [
                'png',
                180,
                '181818783c3e1e0c',
            ],
            [
                'png',
                270,
                '0008387ffff06000',
            ],
        ];
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: rotate()
     *
     * @dataProvider providerExamples
     *
     * @param string $type
     * @param int    $degrees
     * @param string $hash
     *
     * @return void
     * @throws Exception
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdRotate(
        string $type,
        int $degrees,
        string $hash
    ): void {
        $this->checkJpegSupport($this);
        $images    = $this->getImages();
        $outputDir = 'image/gd';
        $imagePath = $images[$type];

        $resultImage = 'rotate-' . $degrees . '.' . $type;
        $output      = self::outputDir($outputDir . '/' . $resultImage);

        $image = new Gd($imagePath);

        $image->rotate($degrees)
              ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        $this->safeDeleteFile($output);
    }
}
