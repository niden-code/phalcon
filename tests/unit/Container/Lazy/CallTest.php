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

use Phalcon\Container\Lazy\Call;

class CallTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyCall(): void
    {
        $lazy   = new Call(function ($container) {
            return true;
        });
        $actual = $this->actual($lazy);
        $this->assertTrue($actual);
    }
}
