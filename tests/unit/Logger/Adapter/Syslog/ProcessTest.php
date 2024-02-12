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

use Codeception\Stub;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use LogicException;
use Phalcon\Logger\Adapter\Syslog;
use Phalcon\Logger\Enum;
use Phalcon\Logger\Item;
use PHPUnit\Framework\TestCase;
use UnitTester;

use function date_default_timezone_get;

final class ProcessTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Syslog :: process()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerAdapterSyslogProcess(): void
    {
        $streamName = getNewFileName2('log');
        $timezone   = date_default_timezone_get();
        $datetime   = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $adapter    = new Syslog($streamName);

        $item = new Item(
            'Message 1',
            'debug',
            Enum::DEBUG,
            $datetime
        );

        $adapter->process($item);

        $actual = $adapter->close();
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Logger\Adapter\Syslog :: process() - exception
     *
     * @return void
     * @throws Exception
     */
    public function testLoggerAdapterSyslogProcessException()
    {
        $fileName = getNewFileName2('log');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            "Cannot open syslog for name ["
            . $fileName
            . "] and facility [8]"
        );

        $adapter = Stub::construct(
            Syslog::class,
            [
                $fileName,
            ],
            [
                'openlog' => false,
            ]
        );

        $timezone = date_default_timezone_get();
        $datetime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $item     = new Item(
            'Message 1',
            'debug',
            Enum::DEBUG,
            $datetime
        );
        $adapter->process($item);
    }
}
