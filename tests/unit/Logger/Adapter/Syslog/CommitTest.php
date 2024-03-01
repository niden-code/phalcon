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

namespace Phalcon\Tests\Unit\Logger\Adapter\Syslog;

use Phalcon\Logger\Adapter\Syslog;
use Phalcon\Logger\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use UnitTester;

final class CommitTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Syslog :: commit()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerAdapterSyslogCommit(): void
    {
        $streamName = $this->getNewFileName('log');

        $adapter = new Syslog($streamName);

        $adapter->begin();

        $actual = $adapter->inTransaction();
        $this->assertTrue($actual);

        $adapter->commit();

        $actual = $adapter->inTransaction();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Logger\Adapter\Syslog :: commit() - no transaction
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerAdapterSyslogCommitNoTransaction(): void
    {
        $streamName = $this->getNewFileName('log');
        $adapter = new Syslog($streamName);

        $actual = $adapter->inTransaction();
        $this->assertFalse($actual);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('There is no active transaction');

        $adapter->commit();
    }
}
