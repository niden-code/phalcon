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

namespace Phalcon\Tests\Unit\Assets\Filters\None;

use Phalcon\Assets\Filters\None;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class FilterTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Assets\Filters\None :: filter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsFiltersNoneFilter(): void
    {
        $filter = new None();

        $expected = ' ';
        $actual   = $filter->filter(' ');
        $this->assertSame($expected, $actual);
    }
}
