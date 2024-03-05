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
final class GetImageTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Imagick :: getImage()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-02-19
     */
    public function testImageAdapterImagickGetImage(): void
    {
        $image = new Imagick(
            self::dataDir('assets/images/example-jpg.jpg')
        );

        $class  = \Imagick::class;
        $actual = $image->getImage();
        $this->assertInstanceOf($class, $actual);
    }
}
