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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Traits\GdTrait2;

#[RequiresPhpExtension('gd')]
final class GetWidthTest extends AbstractUnitTestCase
{
    use GdTrait2;

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                self::dataDir('assets/images/example-gif.gif'),
                960,
            ],
            [
                self::dataDir('assets/images/example-jpg.jpg'),
                1820,
            ],
            [
                self::dataDir('assets/images/example-png.png'),
                82,
            ],
            [
                self::dataDir('assets/images/example-wbmp.wbmp'),
                640,
            ],
            [
                self::dataDir('assets/images/example-webp.webp'),
                1536,
            ],
            [
                self::dataDir('assets/images/example-xbm.xbm'),
                206,
            ],
        ];
    }

    /**
     * Tests Phalcon\Image\Adapter\Gd :: getWidth()
     *
     * @dataProvider providerExamples
     *
     * @param
     * @param Example $example
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2018-11-13
     */
    public function testImageAdapterGdGetWidth(
        string $source,
        int $expected
    ): void {
        $this->checkJpegSupport($this);

        $gd = new Gd($source);

        $actual = $gd->getWidth();
        $this->assertSame($expected, $actual);
    }
}
