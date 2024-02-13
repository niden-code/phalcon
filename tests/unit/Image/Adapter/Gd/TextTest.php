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

use function safeDeleteFile2;

#[RequiresPhpExtension('gd')]
final class TextTest extends TestCase
{
    use GdTrait2;

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                1,
                'Hello Phalcon!',
                false,
                false,
                100,
                '000000',
                12,
                null,
                'fbf9f3e3c3c18183',
            ],
            [
                2,
                'Hello Phalcon!',
                50,
                false,
                100,
                '000000',
                12,
                null,
                'fbf9f3e3c3c18183',
            ],
            [
                3,
                'Hello Phalcon!',
                50,
                75,
                100,
                '000000',
                12,
                null,
                'fbf9f3e3c3c18183',
            ],
            [
                4,
                'Hello Phalcon!',
                50,
                75,
                60,
                '000000',
                12,
                null,
                'fbf9f3e3c3c18183',
            ],
            [
                5,
                'Hello Phalcon!',
                50,
                75,
                60,
                '00FF00',
                12,
                null,
                'fbf9f3e3c3c18183',
            ],
            [
                6,
                'Hello Phalcon!',
                50,
                75,
                60,
                '0000FF',
                24,
                null,
                'fbf9f3e3c3c18183',
            ],
        ];
    }

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
    public function testImageAdapterGdText(
        int $index,
        string $text,
        false | int $offsetX,
        false | int $offsetY,
        int $opacity,
        string $color,
        int $size,
        ?string $font,
        string $hash
    ): void {
        $this->checkJpegSupport($this);

        $outputDir   = 'image/gd';
        $image       = new Gd(dataDir2('assets/images/example-jpg.jpg'));
        $outputImage = $index . 'text.jpg';
        $output      = outputDir2($outputDir . '/' . $outputImage);

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

        $this->assertFileExists($output);

        $actual = $this->checkImageHash($output, $hash);
        $this->assertTrue($actual);

        safeDeleteFile2($output);
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
    public function testImageAdapterGdTextWithFont(): void
    {
        $this->checkJpegSupport($this);

        $outputDir = 'image/gd';

        $image       = dataDir2('assets/images/example-jpg.jpg');
        $outputImage = '15188-text.jpg';
        $output      = outputDir2($outputDir . '/' . $outputImage);
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

        $this->assertFileExists($output);

        $this->assertTrue($this->checkImageHash($output, $hash));
        safeDeleteFile2($output);
    }
}
