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
final class GetMimeTest extends AbstractUnitTestCase
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
                'expected' => 'image/gif',
            ],
            [
                'source'   => self::dataDir('assets/images/example-jpg.jpg'),
                'expected' => 'image/jpeg',
            ],
            [
                'source'   => self::dataDir('assets/images/example-png.png'),
                'expected' => 'image/png',
            ],
            [
                'source'   => self::dataDir('assets/images/example-wbmp.wbmp'),
                'expected' => 'image/vnd.wap.wbmp',
            ],
            [
                'source'   => self::dataDir('assets/images/example-webp.webp'),
                'expected' => 'image/webp',
            ],
            [
                'source'   => self::dataDir('assets/images/example-xbm.xbm'),
                'expected' => 'image/xbm',
            ],
        ];
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: getMime()
     *
     * @dataProvider providerExamples
     *
     * @param string $source
     * @param string $expected
     *
     * @return void
     *
     * @throws Exception
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdGetMime(
        string $source,
        string $expected
    ): void {
        $this->checkJpegSupport($this);

        $gd = new Gd($source);

        $actual = $gd->getMime();
        $this->assertSame($expected, $actual);
    }
}
