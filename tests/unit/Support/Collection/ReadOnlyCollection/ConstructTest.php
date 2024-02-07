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

namespace Phalcon\Tests\Unit\Support\Collection\ReadOnlyCollection;

use Phalcon\Support\Collection;
use Phalcon\Support\Collection\ReadOnlyCollection;
use PHPUnit\Framework\TestCase;

final class ConstructTest extends TestCase
{
    /**
     * Tests Phalcon\Support\Collection :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionConstruct(): void
    {
        $collection = new ReadOnlyCollection();

        $this->assertInstanceOf(Collection::class, $collection);
    }
}
