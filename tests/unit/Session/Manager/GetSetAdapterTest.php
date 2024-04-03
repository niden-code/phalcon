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

namespace Phalcon\Tests\Unit\Session\Manager;

use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Session\Manager;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;
use SessionHandlerInterface;

final class GetSetAdapterTest extends AbstractUnitTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Session\Manager :: getAdapter()/setAdapter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionManagerGetSetAdapter(): void
    {
        $manager = new Manager();
        $files   = $this->newService('sessionStream');
        $manager->setAdapter($files);

        $actual = $manager->getAdapter();
        $class  = SessionHandlerInterface::class;
        $this->assertInstanceOf($class, $actual);
    }
}
