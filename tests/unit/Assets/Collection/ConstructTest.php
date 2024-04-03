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
 * Class ConstructTest extends AbstractUnitTestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Collection
 */
final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Assets\Collection :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsCollectionConstruct(): void
    {
        $collection = new Collection();
        $this->assertInstanceOf(Collection::class, $collection);
    }
}
