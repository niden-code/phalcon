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

use Phalcon\Container\Lazy\ArrayValues;
use Phalcon\Container\Lazy\Env;

class ArrayValuesTest extends LazyTestCase
{
    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testContainerLazyArrayValues(): void
    {
        $varname = 'CAPSULE_DI_FOO';
        $lazy    = new ArrayValues([$varname => new Env($varname)]);
        $this->assertFalse(isset($lazy['one']));
        $lazy['one'] = 'two';
        $this->assertTrue(isset($lazy['one']));
        $this->assertSame('two', $lazy['one']);
        unset($lazy['one']);
        $this->assertFalse(isset($lazy['one']));
        $lazy[] = 'three';
        $this->assertCount(2, $lazy);
        $this->assertSame('three', $lazy[0]);

        foreach ($lazy as $key => $value) {
            if ($key === $varname) {
                $this->assertInstanceOf(Env::class, $value);
            }
        }

        $value = random_int(1, 100);
        putenv("CAPSULE_DI_FOO={$value}");
        $expected = [$varname => $value, 0 => 'three'];
        $actual = $this->actual($lazy);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testContainerLazyArrayValuesMerge(): void
    {
        $lazy = new ArrayValues(['one', 'two', 'three' => 'four']);
        $lazy->merge(['five', 'six', 'seven' => 'eight']);
        $expected = ['one', 'two', 'three' => 'four', 'five', 'six', 'seven' => 'eight'];
        $actual = $lazy($this->container);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testContainerLazyArrayValuesRecursion(): void
    {
        $lazy = new ArrayValues([
            'one' => new Env('CAPSULE_DI_FOO', 'int'),
            ['two' => new Env('CAPSULE_DI_BAR', 'int')],
            'three' => 'four',
        ]);
        $one  = random_int(1, 100);
        putenv("CAPSULE_DI_FOO={$one}");
        $two = random_int(1, 100);
        putenv("CAPSULE_DI_BAR={$two}");
        $expected = ['one' => $one, ['two' => $two], 'three' => 'four'];
        $actual = $lazy($this->container);
        $this->assertSame($expected, $actual);
    }
}
