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
use Phalcon\Logger\Logger;
use PHPUnit\Framework\TestCase;
use UnitTester;

use function file_get_contents;

final class AddAdapterTest extends TestCase
{
    /**
     * Tests Phalcon\Logger :: addAdapter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerAddAdapter(): void
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
            ]
        );

        $expected = 1;
        $actual   = $logger->getAdapters();
        $this->assertCount($expected, $actual);

        $logger->addAdapter('two', $adapter2);
        $expected = 2;
        $actual   = $logger->getAdapters();
        $this->assertCount($expected, $actual);

        $logger->debug('Hello');

        $adapter1->close();
        $adapter2->close();

        $content = file_get_contents($outputPath . $fileName1);
        $this->assertStringContainsString('Hello', $content);
        safeDeleteFile2($fileName1);

        $content = file_get_contents($outputPath . $fileName2);
        $this->assertStringContainsString('Hello', $content);
        safeDeleteFile2($fileName2);
    }
}
