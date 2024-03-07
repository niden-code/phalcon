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

use Phalcon\Container\Lazy\NewInstance;
use stdClass;

class NewInstanceTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyNewInstance(): void
    {
        $lazy = new NewInstance(stdClass::class);
        $new1 = $this->actual($lazy);
        $this->assertInstanceOf(stdClass::class, $new1);
        $new2 = $this->actual($lazy);
        $this->assertInstanceOf(stdClass::class, $new2);
        $this->assertNotSame($new1, $new2);
    }
}
