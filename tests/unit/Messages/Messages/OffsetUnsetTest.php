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

namespace Phalcon\Tests\Unit\Messages\Messages;

use Phalcon\Messages\Message;
use Phalcon\Messages\Messages;
use PHPUnit\Framework\TestCase;

final class OffsetUnsetTest extends TestCase
{
    /**
     * Tests Phalcon\Messages\Messages :: offsetUnset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessagesOffsetUnset(): void
    {
        $messages = new Messages(
            [
                0 => new Message(
                    'This is a message #1',
                    'MyField1',
                    'MyType1',
                    111,
                    [
                        'My1' => 'Metadata1',
                    ]
                ),
                1 => new Message(
                    'This is a message #2',
                    'MyField2',
                    'MyType2',
                    222,
                    [
                        'My2' => 'Metadata2',
                    ]
                ),
            ]
        );

        $this->assertCount(2, $messages);

        $messages->offsetUnset(0);

        /**
         * Unset discards the offset so we need to get 0 again
         */
        $message = $messages->offsetGet(0);

        $this->assertInstanceOf(Message::class, $message);

        $expected = 'This is a message #2';
        $actual   = $message->getMessage();
        $this->assertSame($expected, $actual);

        $expected = 'MyField2';
        $actual   = $message->getField();
        $this->assertSame($expected, $actual);

        $expected = 'MyType2';
        $actual   = $message->getType();
        $this->assertSame($expected, $actual);

        $expected = 222;
        $actual   = $message->getCode();
        $this->assertSame($expected, $actual);

        $expected = ['My2' => 'Metadata2'];
        $actual   = $message->getMetaData();
        $this->assertSame($expected, $actual);
    }
}
