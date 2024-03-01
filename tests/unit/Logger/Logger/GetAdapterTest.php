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
use Phalcon\Tests\Support\AbstractUnitTestCase;
use UnitTester;

use function file_get_contents;
use function logsDir;
use function logsDir2;

final class GetAdapterTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger :: getAdapter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerGetAdapter(): void
    {
        $fileName1  = $this->getNewFileName('log');
        $outputPath = $this->logsDir();
        $adapter1   = new Stream($outputPath . $fileName1);

        $logger = new Logger(
            'my-logger',
            [
                'one' => $adapter1,
            ]
        );


        $class  = Stream::class;
        $actual = $logger->getAdapter('one');
        $this->assertInstanceOf($class, $actual);

        $adapter1->close();
        $this->safeDeleteFile($outputPath . $fileName1);
    }

    /**
     * Tests Phalcon\Logger :: getAdapter() - unknown
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerGetAdapterUnknown(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Adapter does not exist for this logger');

        $logger = new Logger('my-logger');
        $logger->getAdapter('unknown');
    }

    /**
     * Tests Phalcon\Logger :: getAdapter() - for transaction
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerGetAdapterForTransaction(): void
    {
        $fileName1  = $this->getNewFileName('log');
        $fileName2  = $this->getNewFileName('log');
        $outputPath = $this->logsDir();

        $adapter1 = new Stream($outputPath . $fileName1);
        $adapter2 = new Stream($outputPath . $fileName2);

        $logger = new Logger(
            'my-logger',
            [
                'one' => $adapter1,
                'two' => $adapter2,
            ]
        );

        $logger->info('Logging');

        $logger->getAdapter('two')
               ->begin()
        ;

        $this->assertFalse(
            $logger->getAdapter('one')
                   ->inTransaction()
        );
        $this->assertTrue(
            $logger->getAdapter('two')
                   ->inTransaction()
        );

        $logger->info('Thanks');
        $logger->info('for');
        $logger->info('Phlying');
        $logger->info('with');
        $logger->info('Phalcon');

        $content = file_get_contents($outputPath . $fileName1);
        $this->assertStringContainsString('Logging', $content);
        $this->assertStringContainsString('Thanks', $content);
        $this->assertStringContainsString('for', $content);
        $this->assertStringContainsString('Phlying', $content);
        $this->assertStringContainsString('with', $content);
        $this->assertStringContainsString('Phalcon', $content);

        $content = file_get_contents($outputPath . $fileName2);
        $this->assertStringNotContainsString('Thanks', $content);
        $this->assertStringNotContainsString('for', $content);
        $this->assertStringNotContainsString('Phlying', $content);
        $this->assertStringNotContainsString('with', $content);
        $this->assertStringNotContainsString('Phalcon', $content);

        $logger->getAdapter('two')
               ->commit()
        ;

        $content = file_get_contents($outputPath . $fileName2);
        $this->assertStringContainsString('Thanks', $content);
        $this->assertStringContainsString('for', $content);
        $this->assertStringContainsString('Phlying', $content);
        $this->assertStringContainsString('with', $content);
        $this->assertStringContainsString('Phalcon', $content);

        $adapter1->close();
        $adapter2->close();

        $this->safeDeleteFile($outputPath . $fileName1);
        $this->safeDeleteFile($outputPath . $fileName2);
    }
}
