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
use Phalcon\Tests\Support\AbstractUnitTestCase;

/**
 * Class IsSetAutoVersionTest extends AbstractUnitTestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Collection
 */
final class IsSetAutoVersionTest extends AbstractUnitTestCase
{
    /**
     * Unit Tests Phalcon\Assets\Collection :: isAutoVersion() /
     * setAutoVersion()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsCollectionIsSetAutoVersion(): void
    {
        $collection = new Collection();
        $this->assertFalse($collection->isAutoVersion());

        $collection->setAutoVersion(true);
        $this->assertTrue($collection->isAutoVersion());
    }
}
