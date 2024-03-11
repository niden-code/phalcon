<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Di;

use Phalcon\Di\Di;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class GetSetDefaultResetTest extends AbstractUnitTestCase
{
    /**
     * Unit Tests Phalcon\Di :: getDefault()/setDefault()/reset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiGetSetDefaultReset(): void
    {
        $class  = Di::class;
        $actual = Di::getDefault();
        $this->assertInstanceOf($class, $actual);

        $container = new Di();

        // delete it
        Di::reset();
        $actual = Di::getDefault();
        $this->assertNotSame($container, $actual);

        // set it again
        Di::setDefault($container);

        $actual = Di::getDefault();
        $this->assertInstanceOf($class, $actual);
        $this->assertSame($container, $actual);
    }
}
