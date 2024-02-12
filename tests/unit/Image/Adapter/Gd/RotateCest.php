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

use Codeception\Example;
use Phalcon\Image\Adapter\Gd;
use Phalcon\Image\Exception;
use Phalcon\Tests\Fixtures\Traits\GdTrait;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use PHPUnit\Framework\TestCase;

use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class RotateTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: rotate()
     *
     * @dataProvider providerExamples
     *
     * @param int    $degrees
     * @param string $hash
     * @param string $type
     *
     * @return void
     * @throws Exception
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    /**
     */
    public function testImageAdapterGdRotate(
        string $type,
        int $degrees,
        string $hash
    ): void {
        $this->checkJpegSupport($this);
        $images    = $this->getImages();
        $outputDir = 'tests/image/gd';
        $imagePath = $images[$type];

        $resultImage = 'rotate-' . $degrees . '.' . $type;
        $output      = outputDir($outputDir . '/' . $resultImage);

        $image = new Gd($imagePath);

        $image->rotate($degrees)
              ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        safeDeleteFile2($output);
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'label'   => 'jpg',
                'degrees' => 0,
                'hash'    => 'fbf9f3e3c3c18183',
            ],
            [
                'label'   => 'jpg',
                'degrees' => 45,
                'hash'    => '60f0f83c1c0f0f06',
            ],
            [
                'label'   => 'jpg',
                'degrees' => 90,
                'hash'    => 'ff3f0f0703009dff',
            ],
            [
                'label'   => 'jpg',
                'degrees' => 180,
                'hash'    => 'c18183c3c7cf9fdf',
            ],
            [
                'label'   => 'jpg',
                'degrees' => 270,
                'hash'    => 'ffb900c0e0f0fcff',
            ],
            [
                'label'   => 'png',
                'degrees' => 0,
                'hash'    => '30787c3c1e181818',
            ],
            [
                'label'   => 'png',
                'degrees' => 45,
                'hash'    => '001c1c1c7c3c0000',
            ],
            [
                'label'   => 'png',
                'degrees' => 90,
                'hash'    => '00060ffffe1c1000',
            ],
            [
                'label'   => 'png',
                'degrees' => 180,
                'hash'    => '181818783c3e1e0c',
            ],
            [
                'label'   => 'png',
                'degrees' => 270,
                'hash'    => '0008387ffff06000',
            ],
        ];
    }
}
