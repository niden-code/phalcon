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

use Closure;
use Phalcon\Container\Lazy\CallableNew;
use stdClass;

class CallableNewTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyCallableNew(): void
    {
        $lazy     = new CallableNew(stdClass::class);
        $callable = $this->actual($lazy);
        $this->assertInstanceOf(Closure::class, $callable);
        $new1 = $callable();
        $this->assertInstanceOf(stdClass::class, $new1);
        $new2 = $callable();
        $this->assertInstanceOf(stdClass::class, $new2);
        $this->assertNotSame($new1, $new2);
    }
}
