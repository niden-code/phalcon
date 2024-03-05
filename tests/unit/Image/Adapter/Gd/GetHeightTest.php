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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;

#[RequiresPhpExtension('gd')]
final class GetHeightTest extends AbstractUnitTestCase
{
    use GdTrait2;

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'source'   => self::dataDir('assets/images/example-gif.gif'),
                'expected' => 640,
            ],
            [
                'source'   => self::dataDir('assets/images/example-jpg.jpg'),
                'expected' => 694,
            ],
            [
                'source'   => self::dataDir('assets/images/example-png.png'),
                'expected' => 82,
            ],
            [
                'source'   => self::dataDir('assets/images/example-wbmp.wbmp'),
                'expected' => 426,
            ],
            [
                'source'   => self::dataDir('assets/images/example-webp.webp'),
                'expected' => 1024,
            ],
            [
                'source'   => self::dataDir('assets/images/example-xbm.xbm'),
                'expected' => 187,
            ],
        ];
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: getHeight()
     *
     * @dataProvider providerExamples
     *
     * @param string $source
     * @param int    $expected
     *
     * @return void
     *
     * @throws Exception
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdGetHeight(
        string $source,
        int $expected
    ): void {
        $this->checkJpegSupport($this);

        $gd = new Gd($source);

        $actual = $gd->getHeight();
        $this->assertSame($expected, $actual);
    }
}
