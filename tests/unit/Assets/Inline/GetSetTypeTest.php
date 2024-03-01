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
use Phalcon\Tests\Support\AbstractUnitTestCase;

/**
 * Class GetSetTypeTest extends AbstractUnitTestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Inline
 */
final class GetSetTypeTest extends AbstractUnitTestCase
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
                'js',
            ],
            [
                'js',
                '<script>alert("Hello");</script>',
                'css',
            ],
        ];
    }

    /**
     * Tests Phalcon\Assets\Inline\Css :: getType()/setType()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssGetSetType(): void
    {
        $asset   = new Css('p {color: #000099}');
        $newType = 'js';

        $asset->setType($newType);
        $actual = $asset->getType();

        $this->assertSame($newType, $actual);
    }

    /**
     * Tests Phalcon\Assets\Inline :: getType()/setType()
     *
     * @dataProvider provider
     *
     * @param Example $example
     *
     * @return void
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testAssetsInlineGetSetType(
        string $type,
        string $content,
        string $newType
    ): void {
        $asset = new Inline($type, $content);
        $asset->setType($newType);

        $expected = $newType;
        $actual   = $asset->getType();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Assets\Inline\Js :: getType()/setType()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineJsGetSetType(): void
    {
        $asset   = new Js('<script>alert("Hello");</script>');
        $newType = 'js';

        $asset->setType($newType);
        $actual = $asset->getType();

        $this->assertSame($newType, $actual);
    }
}
