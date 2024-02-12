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

#[RequiresPhpExtension('gd')]
final class GetRealpathTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: getRealpath()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testImageAdapterGdGetRealpath(): void
    {
        $this->checkJpegSupport($this);

        foreach ($this->getImages() as $image) {
            $image = str_replace("/", DIRECTORY_SEPARATOR, $image);
            $gd    = new Gd($image);

            $actual = $gd->getRealpath();
            $this->assertSame($image, $actual);
        }
    }
}
