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
use Phalcon\Container\Lazy\Env;
use Random\RandomException;

class EnvTest extends LazyTestCase
{
    /**
     * @return void
     * @throws RandomException
     */
    public function testContainerLazyEnv(): void
    {
        $varname  = 'CAPSULE_DI_FOO';
        $lazy     = new Env($varname);
        $expected = random_int(1, 100);
        putenv("CAPSULE_DI_FOO={$expected}");
        $actual = $this->actual($lazy);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     * @throws RandomException
     */
    public function testContainerLazyEnvNoSuchVar(): void
    {
        $varname = 'CAPSULE_DI_' . random_int(1, 100);
        $lazy    = new Env($varname);
        $this->expectException(Exception\NotDefined::class);
        $this->expectExceptionMessage(
            "Evironment variable '{$varname}' is not defined.",
        );
        $this->actual($lazy);
    }

    /**
     * @return void
     * @throws RandomException
     */
    public function testContainerLazyEnvType(): void
    {
        $varname  = 'CAPSULE_DI_FOO';
        $lazy     = new Env($varname, 'int');
        $expected = random_int(1, 100);
        putenv("CAPSULE_DI_FOO={$expected}");
        $actual = $this->actual($lazy);
        $this->assertSame($expected, $actual);
    }
}
