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

use Phalcon\Container\Lazy\FunctionCall;

function fake(string $word): string
{
    return $word;
}

class FunctionCallTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyFunctionCall(): void
    {
        $lazy   = new FunctionCall('Phalcon\Tests\Unit\Container\Lazy\fake', ['two']);
        $actual = $this->actual($lazy);
        $this->assertSame('two', $actual);
    }
}
