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

use Codeception\Example;
use Phalcon\Image\Adapter\Gd;
use Phalcon\Image\Exception;
use Phalcon\Tests\Fixtures\Traits\GdTrait;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use PHPUnit\Framework\TestCase;

use const IMAGETYPE_GIF;
use const IMAGETYPE_JPEG;
use const IMAGETYPE_PNG;
use const IMAGETYPE_WBMP;
use const IMAGETYPE_WEBP;
use const IMAGETYPE_XBM;

#[RequiresPhpExtension('gd')]
final class GetTypeTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: getType()
     *
     * @dataProvider providerExamples
     *
     * @param string $source
     * @param int $expected
     *
     * @return void
     *
     * @throws Exception
     * @since        2022-07-19
     * @author       Phalcon Team <team@phalcon.io>
     */
    /**
     */
    public function imageAdapterGdGetType(
        string $source,
        int $expected
    ): void {
        $this->checkJpegSupport($this);

        $gd = new Gd($source);

        $actual = $gd->getType();
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'source'   => dataDir2('assets/images/example-gif.gif'),
                'expected' => IMAGETYPE_GIF,
            ],
            [
                'source'   => dataDir2('assets/images/example-jpg.jpg'),
                'expected' => IMAGETYPE_JPEG,
            ],
            [
                'source'   => dataDir2('assets/images/example-png.png'),
                'expected' => IMAGETYPE_PNG,
            ],
            [
                'source'   => dataDir2('assets/images/example-wbmp.wbmp'),
                'expected' => IMAGETYPE_WBMP,
            ],
            [
                'source'   => dataDir2('assets/images/example-webp.webp'),
                'expected' => IMAGETYPE_WEBP,
            ],
            [
                'source'   => dataDir2('assets/images/example-xbm.xbm'),
                'expected' => IMAGETYPE_XBM,
            ],
        ];
    }
}
