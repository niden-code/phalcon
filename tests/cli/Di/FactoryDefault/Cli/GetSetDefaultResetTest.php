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

namespace Phalcon\Tests\Cli\Di\FactoryDefault\Cli;

use Phalcon\Di\FactoryDefault\Cli as Di;
use Phalcon\Tests\Support\AbstractCliTestCase;

use function spl_object_hash;

class GetSetDefaultResetTest extends AbstractCliTestCase
{
    /**
     * Tests Phalcon\Di\FactoryDefault\Cli :: getDefault()/setDefault()/reset()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testDiFactorydefaultCliGetDefault(): void
    {
        // there is a DI container
        $this->assertInstanceOf(Di::class, Di::getDefault());

        $container = Di::getDefault();

        // delete it
        Di::reset();

        // Get a new container
        $new = Di::getDefault();

        $this->assertNotSame($new, $container);

        // set it again
        Di::setDefault($container);

        $class  = Di::class;
        $actual = Di::getDefault();
        $this->assertInstanceOf($class, $actual);

        $containerHash = spl_object_hash($container);
        $currentHash   = spl_object_hash($actual);
        $this->assertSame($containerHash, $currentHash);
    }
}
