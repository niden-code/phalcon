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

namespace Phalcon\Tests\Unit\Assets\Collection;

use Phalcon\Assets\Collection;
use Phalcon\Assets\Inline;
use PHPUnit\Framework\TestCase;

/**
 * Class AddInlineTest extends TestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Collection
 */
final class AddInlineTest extends TestCase
{
    /**
     * Tests Phalcon\Assets\Collection :: addInline()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsCollectionAddInline(): void
    {
        $collection = new Collection();
        $inline     = new Inline('js', "alert('an amazing test');");
        $collection->addInline($inline);

        $codes = $collection->getCodes();

        $this->assertCount(1, $collection->getCodes());
        $this->assertSame(end($codes), $inline);
    }
}
