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

use Phalcon\Container\Definition\Definitions;
use Phalcon\Container\Lazy\NewCall;
use Phalcon\Tests1\Fixtures\Container\ChildClass;

use function uniqid;

class NewCallTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyNewCall(): void
    {
        $lazy   = new NewCall(ChildClass::class, 'getValue', []);
        $actual = $this->actual($lazy);
        $this->assertSame('ten', $this->actual($lazy));
    }

    /**
     * @return Definitions
     */
    protected function definitions(): Definitions
    {
        $value = uniqid('val-');

        $def = parent::definitions();
        $def->{ChildClass::class}->argument('one', $value);

        return $def;
    }
}
