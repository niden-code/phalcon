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
use Phalcon\Image\Exception;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use PHPUnit\Framework\TestCase;

use function outputDir;
use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class TextTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: text()
     *
     * @dataProvider providerExamples
     *
     * @param int         $index
     * @param string      $text
     * @param bool        $offsetX
     * @param bool        $offsetY
     * @param int         $opacity
     * @param string      $color
     * @param int         $size
     * @param string|null $font
     * @param string      $hash
     *
     * @return void
     * @throws Exception
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function imageAdapterGdText(
        int $index,
        string $text,
        false|int $offsetX,
        false|int $offsetY,
        int $opacity,
        string $color,
        int $size,
        ?string $font,
        string $hash
    ): void {
        $this->checkJpegSupport($this);

        $outputDir   = 'tests/image/gd';
        $image       = new Gd(dataDir2('assets/images/example-jpg.jpg'));
        $outputImage = $index . 'text.jpg';
        $output      = outputDir($outputDir . '/' . $outputImage);

        $image
            ->text(
                $text,
                $offsetX,
                $offsetY,
                $opacity,
                $color,
                $size,
                $font
            )
            ->save($output)
        ;

        $this->assertFileExists($outputImage);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        safeDeleteFile2($outputImage);
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: text()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-04-20
     * @issue  15188
     */
    public function imageAdapterGdTextWithFont(): void
    {
        $this->checkJpegSupport($this);

        $outputDir = 'tests/image/gd';

        $image       = dataDir2('assets/images/example-jpg.jpg');
        $outputImage = '15188-text.jpg';
        $output      = outputDir($outputDir . '/' . $outputImage);
        $text        = 'Hello Phalcon!';
        $offsetX     = 50;
        $offsetY     = 75;
        $opacity     = 60;
        $color       = '0000FF';
        $size        = 24;
        $font        = dataDir2('assets/fonts/Roboto-Light.ttf');
        $hash        = 'fbf9f3e3c3c18183';

        $object = new Gd($image);
        $object
            ->text($text, $offsetX, $offsetY, $opacity, $color, $size, $font)
            ->save($output)
        ;

        $this->assertFileExists($outputImage);

        $this->assertTrue($this->checkImageHash($output, $hash));
        safeDeleteFile2($outputImage);
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'index'   => 1,
                'text'    => 'Hello Phalcon!',
                'offsetX' => false,
                'offsetY' => false,
                'opacity' => 100,
                'color'   => '000000',
                'size'    => 12,
                'font'    => null,
                'hash'    => 'fbf9f3e3c3c18183',
            ],
            [
                'index'   => 2,
                'text'    => 'Hello Phalcon!',
                'offsetX' => 50,
                'offsetY' => false,
                'opacity' => 100,
                'color'   => '000000',
                'size'    => 12,
                'font'    => null,
                'hash'    => 'fbf9f3e3c3c18183',
            ],
            [
                'index'   => 3,
                'text'    => 'Hello Phalcon!',
                'offsetX' => 50,
                'offsetY' => 75,
                'opacity' => 100,
                'color'   => '000000',
                'size'    => 12,
                'font'    => null,
                'hash'    => 'fbf9f3e3c3c18183',
            ],
            [
                'index'   => 4,
                'text'    => 'Hello Phalcon!',
                'offsetX' => 50,
                'offsetY' => 75,
                'opacity' => 60,
                'color'   => '000000',
                'size'    => 12,
                'font'    => null,
                'hash'    => 'fbf9f3e3c3c18183',
            ],
            [
                'index'   => 5,
                'text'    => 'Hello Phalcon!',
                'offsetX' => 50,
                'offsetY' => 75,
                'opacity' => 60,
                'color'   => '00FF00',
                'size'    => 12,
                'font'    => null,
                'hash'    => 'fbf9f3e3c3c18183',
            ],
            [
                'index'   => 6,
                'text'    => 'Hello Phalcon!',
                'offsetX' => 50,
                'offsetY' => 75,
                'opacity' => 60,
                'color'   => '0000FF',
                'size'    => 24,
                'font'    => null,
                'hash'    => 'fbf9f3e3c3c18183',
            ],
        ];
    }
}
