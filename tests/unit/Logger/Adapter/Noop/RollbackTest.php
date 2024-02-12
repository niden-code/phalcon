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

namespace Phalcon\Tests\Unit\Logger\Adapter\Noop;

use Phalcon\Logger\Adapter\Noop;
use PHPUnit\Framework\TestCase;
use UnitTester;

final class RollbackTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Noop :: rollback()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerAdapterNoopRollback(): void
    {
        $adapter = new Noop();

        $adapter->begin();

        $actual = $adapter->inTransaction();
        $this->assertTrue($actual);

        $adapter->rollback();

        $actual = $adapter->inTransaction();
        $this->assertFalse($actual);
    }
}
