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

use Phalcon\Messages\Exception;
use Phalcon\Messages\Message;
use Phalcon\Messages\Messages;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class OffsetGetSetTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Messages\Messages :: offsetGet()/offsetSet()
     *
     * @return void
     *
     * @throws Exception
     * @since  2020-09-09
     * @author Phalcon Team <team@phalcon.io>
     */
    public function testMessagesMessagesOffsetGetSet(): void
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

        $messages->offsetSet(
            2,
            new Message(
                'This is a message #3',
                'MyField3',
                'MyType3',
                777,
                [
                    'My3' => 'Metadata3',
                ]
            )
        );
        $this->assertCount(3, $messages);

        $message = $messages->offsetGet(2);
        $this->assertInstanceOf(Message::class, $message);


        $expected = 'This is a message #3';
        $actual   = $message->getMessage();
        $this->assertSame($expected, $actual);

        $expected = 'MyField3';
        $actual   = $message->getField();
        $this->assertSame($expected, $actual);

        $expected = 'MyType3';
        $actual   = $message->getType();
        $this->assertSame($expected, $actual);

        $expected = 777;
        $actual   = $message->getCode();
        $this->assertSame($expected, $actual);

        $expected = ['My3' => 'Metadata3'];
        $actual   = $message->getMetaData();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Messages\Messages :: offsetSet() - exception
     *
     * @return void
     *
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessagesOffsetSetException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The message must be an object');

        $messages = new Messages();

        $messages->offsetSet(2, 'message');
    }
}
