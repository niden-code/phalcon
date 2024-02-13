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

namespace Phalcon\Tests\Unit\Image\ImageFactory;

use Phalcon\Image\Adapter\Imagick;
use Phalcon\Image\Exception;
use Phalcon\Image\ImageFactory;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

use function uniqid;

#[RequiresPhpExtension('imagick')]
final class NewInstanceTest extends TestCase
{
    /**
     * Tests Phalcon\Image\ImageFactory :: newInstance()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-05-18
     */
    public function testImageImageFactoryNewInstance(): void
    {
        $factory = new ImageFactory();
        $file    = dataDir2('assets/images/example-jpg.jpg');
        $file    = str_replace("/", DIRECTORY_SEPARATOR, $file);
        $name    = 'imagick';

        $image = $factory->newInstance($name, $file);

        $class = Imagick::class;
        $this->assertInstanceOf($class, $image);

        $expected = $file;
        $actual   = $image->getRealPath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Image\ImageFactory :: newInstance() - exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2022-08-02
     */
    public function testImageImageFactoryNewInstanceException(): void
    {
        $name = uniqid('service-');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service ' . $name . ' is not registered');

        $factory = new ImageFactory();
        $factory->newInstance($name, uniqid('file-'));
    }
}
