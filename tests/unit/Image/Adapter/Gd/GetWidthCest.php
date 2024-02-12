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
use Phalcon\Tests\Fixtures\Traits\GdTrait;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;
use PHPUnit\Framework\TestCase;

use function dataDir;

#[RequiresPhpExtension('gd')]
final class GetWidthTest extends TestCase
{
    use GdTrait2;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: getWidth()
     *
     * @dataProvider providerExamples
     *
     * @param
     * @param Example    $example
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function imageAdapterGdGetWidth(
        string $source,
        int $expected
    ): void {
        $this->checkJpegSupport($this);

        $gd = new Gd($source);

        $actual = $gd->getWidth();
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
                'expected' => 960,
            ],
            [
                'source'   => dataDir2('assets/images/example-jpg.jpg'),
                'expected' => 1820,
            ],
            [
                'source'   => dataDir2('assets/images/example-png.png'),
                'expected' => 82,
            ],
            [
                'source'   => dataDir2('assets/images/example-wbmp.wbmp'),
                'expected' => 640,
            ],
            [
                'source'   => dataDir2('assets/images/example-webp.webp'),
                'expected' => 1536,
            ],
            [
                'source'   => dataDir2('assets/images/example-xbm.xbm'),
                'expected' => 206,
            ],
        ];
    }
}
