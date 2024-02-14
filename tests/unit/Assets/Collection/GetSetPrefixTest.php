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
use PHPUnit\Framework\TestCase;

/**
 * Class GetSetPrefixTest extends TestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Collection
 */
final class GetSetPrefixTest extends TestCase
{
    /**
     * Tests Phalcon\Assets\Collection :: getPrefix() / setPrefix()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsCollectionGetSetPrefix(): void
    {
        $collection = new Collection();
        $prefix     = 'phly_';
        $collection->setPrefix($prefix);

        $this->assertSame($prefix, $collection->getPrefix());
    }
}
