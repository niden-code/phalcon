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

namespace Phalcon\Tests\Unit\Logger\Adapter\Stream;

use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use UnitTester;

final class RollbackTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Stream :: rollback()
     *
     * @return void
     *
     * @throws Exception
     * @since  2020-09-09
     * @author Phalcon Team <team@phalcon.io>
     */
    public function testLoggerAdapterStreamRollback(): void
    {
        $fileName   = $this->getNewFileName('log');
        $outputPath = $this->logsDir();
        $adapter    = new Stream($outputPath . $fileName);

        $adapter->begin();

        $actual = $adapter->inTransaction();
        $this->assertTrue($actual);

        $adapter->rollback();

        $actual = $adapter->inTransaction();
        $this->assertFalse($actual);

        $adapter->close();
        $this->safeDeleteFile($outputPath . $fileName);
    }

    /**
     * Tests Phalcon\Logger\Adapter\Stream :: rollback() - exception
     *
     * @return void
     * @throws Exception
     */
    public function testLoggerAdapterStreamRollbackException(): void
    {
        $fileName   = $this->getNewFileName('log');
        $outputPath = $this->logsDir();
        $adapter    = new Stream($outputPath . $fileName);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('There is no active transaction');

        $adapter->rollback();
        $adapter->close();
    }
}
