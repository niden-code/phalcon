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

use DateTimeImmutable;
use DateTimeZone;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Enum;
use Phalcon\Logger\Exception;
use Phalcon\Logger\Item;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use UnitTester;

use function date_default_timezone_get;
use function file_get_contents;

final class CloseTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Stream :: close()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerAdapterStreamClose(): void
    {
        $fileName   = $this->getNewFileName('log');
        $outputPath = $this->logsDir();
        $timezone   = date_default_timezone_get();
        $datetime   = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $adapter    = new Stream($outputPath . $fileName);

        $item = new Item(
            'Message 1',
            'debug',
            Enum::DEBUG,
            $datetime
        );
        $adapter->process($item);

        $actual = $adapter->close();
        $this->assertTrue($actual);

        $content = file_get_contents($outputPath . $fileName);
        $expected = 'Message 1';
        $this->assertStringContainsString($expected, $content);

        $this->safeDeleteFile($outputPath . $fileName);
    }

    /**
     * Tests Phalcon\Logger\Adapter\Stream :: close() - exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-09-03
     * @issue  15638
     */
    public function testLoggerAdapterStreamCloseException(): void
    {
        $fileName   = $this->getNewFileName('log');
        $outputPath = $this->logsDir();
        $adapter    = new Stream($outputPath . $fileName);

        $adapter->begin();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('There is an active transaction');

        $adapter->close();
    }
}
