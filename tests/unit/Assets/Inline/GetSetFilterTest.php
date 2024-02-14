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

namespace Phalcon\Tests\Unit\Assets\Inline;

use Codeception\Example;
use Phalcon\Assets\Inline;
use Phalcon\Assets\Inline\Css;
use Phalcon\Assets\Inline\Js;
use PHPUnit\Framework\TestCase;

/**
 * Class GetSetFilterTest extends TestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Inline
 */
final class GetSetFilterTest extends TestCase
{
    /**
     * @return string[][]
     */
    public static function provider(): array
    {
        return [
            [
                'css',
                'p {color: #000099}',
            ],
            [
                'js',
                '<script>alert("Hello");</script>',
            ],
        ];
    }

    /**
     * Tests Phalcon\Assets\Inline\Css :: getFilter()/setFilter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssGetSetFilter(): void
    {
        $asset  = new Css('p {color: #000099}');
        $actual = $asset->getFilter();
        $this->assertTrue($actual);

        $asset->setFilter(false);

        $actual = $asset->getFilter();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Assets\Inline :: getFilter()/setFilter()
     *
     * @dataProvider provider
     *
     * @param Example $example
     *
     * @return void
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsInlineGetSetFilter(
        string $type,
        string $content
    ): void {
        $asset  = new Inline($type, $content);
        $actual = $asset->getFilter();
        $this->assertTrue($actual);

        $asset->setFilter(false);

        $actual = $asset->getFilter();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Assets\Inline\Js :: getFilter()/setFilter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineJsGetSetFilter(): void
    {
        $asset  = new Js('<script>alert("Hello");</script>');
        $actual = $asset->getFilter();
        $this->assertTrue($actual);

        $asset->setFilter(false);

        $actual = $asset->getFilter();
        $this->assertFalse($actual);
    }
}
