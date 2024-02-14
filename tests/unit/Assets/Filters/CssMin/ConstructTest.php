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

use Phalcon\Assets\FilterInterface;
use Phalcon\Assets\Filters\CssMin;
use PHPUnit\Framework\TestCase;

final class ConstructTest extends TestCase
{
    /**
     * Tests Phalcon\Assets\Filters\CssMin :: __construct() - no string
     * exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsFiltersCssMinConstructNonString(): void
    {
        $cssMin = new Cssmin();
        $this->assertInstanceOf(Cssmin::class, $cssMin);
        $this->assertInstanceOf(FilterInterface::class, $cssMin);
    }
}
