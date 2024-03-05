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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
final class SaveTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: save()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickSave(): void
    {
        $outputFile = self::outputDir('image/imagick/new.jpg');
        $image      = new Imagick($outputFile, 100, 100);
        $image->setResourceLimit(6, 1);
        $image->save();

        $this->assertFileExists($outputFile);

        $this->safeDeleteFile($outputFile);
    }
}
