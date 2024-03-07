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

use Phalcon\Container\Exception;
use Phalcon\Container\Lazy\Get;
use stdClass;

class GetTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyGet(): void
    {
        $lazy = new Get(stdClass::class);
        $get1 = $this->actual($lazy);
        $this->assertInstanceOf(stdClass::class, $get1);
        $get2 = $this->actual($lazy);
        $this->assertInstanceOf(stdClass::class, $get2);
        $this->assertSame($get1, $get2);
    }

    /**
     * @return void
     */
    public function testContainerLazyGetNoSuchClass(): void
    {
        $lazy = new Get('NoSuchClass');
        $this->expectException(Exception\NotFound::class);
        $this->expectExceptionMessage('NoSuchClass');
        $this->actual($lazy);
    }
}
