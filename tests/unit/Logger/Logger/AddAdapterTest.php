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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use UnitTester;

use function file_get_contents;

final class AddAdapterTest extends AbstractUnitTestCase
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
        $fileName1  = $this->getNewFileName('log');
        $fileName2  = $this->getNewFileName('log');
        $outputPath = $this->logsDir();
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
        $this->safeDeleteFile($fileName1);

        $content = file_get_contents($outputPath . $fileName2);
        $this->assertStringContainsString('Hello', $content);
        $this->safeDeleteFile($fileName2);
    }
}
