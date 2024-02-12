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
final class SaveTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: save()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testImageAdapterGdSave(): void
    {
        $this->checkJpegSupport($this);

        $outputDir   = 'tests/image/gd';
        $resultImage = 'save.';

        foreach ($this->getImages() as $type => $imagePath) {
            $image = new Gd($imagePath);

            $output = outputDir($outputDir . '/' . $resultImage . $type);
            $actual = $image->save($output);
            $this->assertInstanceOf(Gd::class, $actual);

            $this->assertFileExists($output);

            safeDeleteFile2($output);
        }
    }
}
