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

namespace Phalcon\Tests\Unit\Logger\Logger;

use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Exception;
use Phalcon\Logger\Logger;
use PHPUnit\Framework\TestCase;
use UnitTester;

final class RemoveAdapterTest extends TestCase
{
    /**
     * Tests Phalcon\Logger :: removeAdapter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerRemoveAdapter(): void
    {
        $fileName1  = getNewFileName2('log');
        $fileName2  = getNewFileName2('log');
        $outputPath = logsDir2();
        $adapter1   = new Stream($outputPath . $fileName1);
        $adapter2   = new Stream($outputPath . $fileName2);

        $logger = new Logger(
            'my-logger',
            [
                'one' => $adapter1,
                'two' => $adapter2,
            ]
        );

        $expected = 2;
        $adapters = $logger->getAdapters();
        $this->assertCount($expected, $adapters);

        $logger->removeAdapter('one');
        $expected = 1;
        $adapters = $logger->getAdapters();
        $this->assertCount($expected, $adapters);

        safeDeleteFile2($outputPath . $fileName1);
        safeDeleteFile2($outputPath . $fileName2);
    }

    /**
     * Tests Phalcon\Logger :: removeAdapter() - unknown
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerRemoveAdapterUnknown(): void
    {
        $fileName1  = getNewFileName2('log');
        $outputPath = logsDir2();

        try {
            $adapter1 = new Stream($outputPath . $fileName1);

            $logger = new Logger(
                'my-logger',
                [
                    'one' => $adapter1,
                ]
            );

            $expected = 1;
            $adapters = $logger->getAdapters();
            $this->assertCount($expected, $adapters);

            $logger->removeAdapter('unknown');
        } catch (Exception $ex) {
            $expected = 'Adapter does not exist for this logger';
            $actual   = $ex->getMessage();
            $this->assertSame($expected, $actual);
        }

        safeDeleteFile2($outputPath . $fileName1);
    }
}
