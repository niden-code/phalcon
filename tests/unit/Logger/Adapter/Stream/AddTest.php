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
use Phalcon\Logger\Item;
use PHPUnit\Framework\TestCase;
use UnitTester;

use function date_default_timezone_get;
use function file_get_contents;

final class AddTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Stream :: add()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testLoggerAdapterStreamAdd(): void
    {
        $fileName   = getNewFileName2('log');
        $outputPath = logsDir2();
        $timezone   = date_default_timezone_get();
        $datetime   = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $adapter    = new Stream($outputPath . $fileName);

        $adapter->begin();
        $item1 = new Item(
            'Message 1',
            'debug',
            Enum::DEBUG,
            $datetime
        );
        $item2 = new Item(
            'Message 2',
            'debug',
            Enum::DEBUG,
            $datetime
        );
        $item3 = new Item(
            'Message 3',
            'debug',
            Enum::DEBUG,
            $datetime
        );

        $adapter
            ->add($item1)
            ->add($item2)
            ->add($item3)
        ;

        $this->assertFileDoesNotExist($outputPath . $fileName);

        $adapter->commit();

        $this->assertFileExists($outputPath . $fileName);

        $content = file_get_contents($outputPath . $fileName);

        $expected = 'Message 1';
        $this->assertStringContainsString($expected, $content);
        $expected = 'Message 2';
        $this->assertStringContainsString($expected, $content);
        $expected = 'Message 3';
        $this->assertStringContainsString($expected, $content);

        $adapter->close();
        safeDeleteFile2($outputPath . $fileName);
    }
}
