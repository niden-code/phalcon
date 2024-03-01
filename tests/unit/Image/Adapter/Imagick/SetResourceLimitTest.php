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
use Phalcon\Image\Exception;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Phalcon\Tests\Support\AbstractUnitTestCase;

#[RequiresPhpExtension('imagick')]
final class SetResourceLimitTest extends AbstractUnitTestCase
{
    /**
     * Unit Tests Phalcon\Image\Adapter\Imagick :: setResourceLimit() -
     * exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2022-08-02
     */
    public function testImageAdapterImagickSetResourceLimitException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot set the Resource Type for this image');

        $source = self::dataDir('assets/images/example-jpg.jpg');
        $image  = new Imagick($source);

        $image->setResourceLimit(100, 1);
    }
}
