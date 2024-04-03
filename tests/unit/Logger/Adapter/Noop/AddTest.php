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

namespace Phalcon\Tests\Unit\Logger\Adapter\Noop;

use DateTimeImmutable;
use DateTimeZone;
use Phalcon\Logger\Adapter\Noop;
use Phalcon\Logger\Enum;
use Phalcon\Logger\Exception;
use Phalcon\Logger\Item;
use PHPUnit\Framework\TestCase;
use UnitTester;

use function date_default_timezone_get;

final class AddTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Noop :: add()
     *
     * @return void
     *
     * @throws Exception
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    /**
     */
    public function testLoggerAdapterNoopAdd(): void
    {
        $timezone = date_default_timezone_get();
        $datetime = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $adapter  = new Noop();

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

        $adapter->commit();
        $actual = $adapter->inTransaction();
        $this->assertFalse($actual);
    }
}
