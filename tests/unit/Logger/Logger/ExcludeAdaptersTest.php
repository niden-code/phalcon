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

final class ExcludeAdaptersTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger :: excludeAdapters()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerExcludeAdapters(): void
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
                'two' => $adapter2,
            ]
        );

        /**
         * Log into both
         */
        $logger->debug('Hello');

        $content1 = file_get_contents($outputPath . $fileName1);
        $this->assertStringContainsString('Hello', $content1);

        $content2 = file_get_contents($outputPath . $fileName2);
        $this->assertStringContainsString('Hello', $content2);

        /**
         * Exclude a logger
         */
        $logger
            ->excludeAdapters(['two'])
            ->debug('Goodbye')
        ;

        /**
         * Get the data from the log files again
         */
        $content1 = file_get_contents($outputPath . $fileName1);
        $this->assertStringContainsString('Goodbye', $content1);

        $content2 = file_get_contents($outputPath . $fileName2);
        $this->assertStringNotContainsString('Goodbye', $content2);

        $adapter1->close();
        $adapter2->close();

        $this->safeDeleteFile($fileName1);
        $this->safeDeleteFile($fileName2);
    }
}
