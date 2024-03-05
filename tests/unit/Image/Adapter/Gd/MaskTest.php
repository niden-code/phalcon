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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;

#[RequiresPhpExtension('gd')]
final class MaskTest extends AbstractUnitTestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: mask()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testImageAdapterGdMask(): void
    {
        $this->checkJpegSupport($this);

        $image = new Gd(self::dataDir('assets/images/example-png.png'));
        $mask  = new Gd(self::dataDir('assets/images/example-jpg.jpg'));

        $outputDir   = 'image/gd';
        $outputImage = 'mask.png';
        $output      = self::outputDir($outputDir . '/' . $outputImage);

        $hash = '30787c3c3f191810';

        // Resize to 200 pixels on the shortest side
        $mask->mask($image)
             ->save($output)
        ;

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        $this->safeDeleteFile($output);
    }
}
