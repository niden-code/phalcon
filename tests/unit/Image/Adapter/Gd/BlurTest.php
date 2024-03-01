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
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class BlurTest extends AbstractUnitTestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: blur()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testImageAdapterGdBlur(): void
    {
        $this->checkJpegSupport($this);

        $params = [
            'gif'  => [
                [1, 'ffffffffffffffff'],
                [2, 'ffffffffffffffff'],
                [5, 'ffffffffffffffff'],
            ],
            'jpg'  => [
                [1, 'fbf9f3e3c3c18183'],
                [2, 'fbf9f3e3c3c18183'],
                [5, 'fbf9f3e3c3c18183'],
            ],
            'png'  => [
                [1, '30787c3c1e1c1818'],
                [2, '30787c3c3e181818'],
                [5, '30787c3c3e181818'],
            ],
            'wbmp' => [
                [1, 'cffffffc182f8201'],
                [2, 'cffffffc182f8201'],
                [5, 'cffffffc182f8201'],
            ],
            'webp' => [
                [1, '070600183c3c7c7c'],
                [2, '070200183c3c3c7c'],
                [5, '070200183c3c3c7c'],
            ],
            'xbm'  => [
                [1, '070600183c3c7c7c'],
                [2, '070200183c3c3c7c'],
                [5, '070200183c3c3c7c'],
            ],
        ];

        $outputDir = 'image/gd';

        foreach ($this->getImages() as $type => $imagePath) {
            foreach ($params[$type] as [$level, $hash]) {
                $resultImage = 'blur-' . $level . '.' . $type;
                $output      = self::outputDir($outputDir . '/' . $resultImage);

                $image = new Gd($imagePath);

                $image->blur($level)
                      ->save($output)
                ;
                $this->assertFileExists($output);

                $actual = $this->checkImageHash($output, $hash);
                $this->assertTrue($actual);

                $this->safeDeleteFile($output);
            }
        }
    }
}
