<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Tests\Unit\Container\Lazy;

use Phalcon\Container\Lazy\StaticCall;
use Phalcon\Tests1\Fixtures\Container\ChildClass;

class StaticCallTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyStaticCall(): void
    {
        $lazy = new StaticCall(ChildClass::class, 'staticFake', ['two']);
        $this->assertSame('two', $this->actual($lazy));
    }
}
