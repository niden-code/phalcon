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
use Phalcon\Image\ImageFactory;
use Phalcon\Tests\Fixtures\Traits\FactoryTrait;
use Phalcon\Tests1\Fixtures\Traits\FactoryTrait2;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

#[RequiresPhpExtension('imagick')]
final class LoadTest extends TestCase
{
    use FactoryTrait2;

    public function setUp(): void
    {
        $this->init();
    }

    /**
     * Tests Phalcon\Image\ImageFactory :: load()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-05-18
     */
    public function imageImageFactoryLoad(): void
    {
        $options = $this->config->image;
        $factory = new ImageFactory();

        /** @var Imagick $image */
        $image = $factory->load($options);

        $class = Imagick::class;
        $this->assertInstanceOf($class, $image);

        $expected = realpath($options->file);
        $actual   = $image->getRealpath();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Image\ImageFactory :: load()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-05-18
     */
    public function imageImageFactoryLoadArray(): void
    {
        $options = $this->arrayConfig['image'];
        $factory = new ImageFactory();

        /** @var Imagick $image */
        $image = $factory->load($options);

        $class = Imagick::class;
        $this->assertInstanceOf($class, $image);

        $expected = realpath($options['file']);
        $actual   = $image->getRealpath();
        $this->assertSame($expected, $actual);
    }
}
