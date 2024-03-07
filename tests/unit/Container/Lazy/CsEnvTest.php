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

use Phalcon\Container\Lazy\CsEnv;

class CsEnvTest extends LazyTestCase
{
    /**
     * @return void
     * @throws \Random\RandomException
     */
    public function testContainerLazyCsEnv(): void
    {
        $varname = 'CAPSULE_DI_FOO';
        $lazy    = new CsEnv($varname, 'int');
        $expected  = array_fill(0, 3, random_int(1, 100));
        putenv("CAPSULE_DI_FOO=" . implode(',', $expected));
        $actual = $this->actual($lazy);
        $this->assertEquals($expected, $actual);
    }
}
