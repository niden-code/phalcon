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

use Phalcon\Tests\Fixtures\Traits\ImagickTrait;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Phalcon\Tests\Support\AbstractUnitTestCase;

#[RequiresPhpExtension('imagick')]
final class PixelateTest extends AbstractUnitTestCase
{
    use ImagickTrait;

    /**
     * Tests Phalcon\Image\Adapter\Imagick :: pixelate()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickPixelate(): void
    {
        $this->markTestSkipped('Need implementation');
    }
}
