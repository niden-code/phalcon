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

final class SetAdaptersTest extends TestCase
{
    /**
     * Tests Phalcon\Logger :: setAdapters()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerSetAdapters(): void
    {
        $fileName1  = getNewFileName2('log');
        $fileName2  = getNewFileName2('log');
        $outputPath = logsDir2();
        $adapter1   = new Stream($outputPath . $fileName1);
        $adapter2   = new Stream($outputPath . $fileName2);

        $logger = new Logger('my-logger');

        $expected = 0;
        $adapters = $logger->getAdapters();
        $this->assertCount($expected, $adapters);

        $logger->setAdapters(
            [
                'one' => $adapter1,
                'two' => $adapter2,
            ]
        );

        $expected = 2;
        $adapters = $logger->getAdapters();
        $this->assertCount($expected, $adapters);

        $class = Stream::class;
        $this->assertInstanceOf($class, $adapters['one']);
        $this->assertInstanceOf($class, $adapters['two']);

        safeDeleteFile2($outputPath . $fileName1);
        safeDeleteFile2($outputPath . $fileName2);
    }

    /**
     * Tests Phalcon\Logger :: setAdapters() - constructor
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerSetAdaptersConstructor(): void
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

        $class = Stream::class;
        $this->assertInstanceOf($class, $adapters['one']);
        $this->assertInstanceOf($class, $adapters['two']);

        safeDeleteFile2($outputPath . $fileName1);
        safeDeleteFile2($outputPath . $fileName2);
    }
}
