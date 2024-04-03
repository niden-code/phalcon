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

use Codeception\Stub;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Session\Manager;
use Phalcon\Tests1\Fixtures\Session\ManagerHeadersSentFixture;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;

/**
 * Class StartTest extends AbstractUnitTestCase
 *
 * @package Phalcon\Tests\Integration\Session\Manager
 */
final class StartTest extends AbstractUnitTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Session\Manager :: start() - headers sent
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionManagerStartHeadersSent(): void
    {
        $manager = new Manager();
        $files   = $this->newService('sessionStream');
        $manager->setAdapter($files);

        $mock = new ManagerHeadersSentFixture();

        $actual = $mock->start();
        $this->assertFalse($actual);

        $manager->destroy();
    }
}
