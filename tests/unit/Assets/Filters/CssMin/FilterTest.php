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

namespace Phalcon\Tests\Unit\Assets\Filters\CssMin;

use Phalcon\Assets\Filters\CssMin;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function dataDir2;
use function file_get_contents;

final class FilterTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Assets\Filters\CssMin :: filter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsFiltersCssMinFilter(): void
    {
        $cssmin = new Cssmin();

        $source   = file_get_contents(self::dataDir('assets/assets/cssmin-01.css'));
        $expected = ".h2:after,.h2:after{content:'';"
            . "display:block;height:1px;width:100%;"
            . "border-color:silver;border-style:solid none;"
            . "border-width:1px;position:absolute;bottom:0;left:0}";
        $actual   = $cssmin->filter($source);
        $this->assertSame($expected, $actual);
    }
}
