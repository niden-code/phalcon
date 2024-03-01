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
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class CurrentTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Messages\Messages :: current()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessagesCurrent(): void
    {
        $messages = new Messages(
            [
                new Message(
                    'This is a message #1',
                    'MyField1',
                    'MyType1',
                    111,
                    [
                        'My1' => 'Metadata1',
                    ]
                ),
                new Message(
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

        $message = $messages->current();

        $this->assertInstanceOf(Message::class, $message);

        $expected = 'This is a message #1';
        $actual   = $message->getMessage();
        $this->assertSame($expected, $actual);

        $expected = 'MyField1';
        $actual   = $message->getField();
        $this->assertSame($expected, $actual);

        $expected = 'MyType1';
        $actual   = $message->getType();
        $this->assertSame($expected, $actual);

        $expected = 111;
        $actual   = $message->getCode();
        $this->assertSame($expected, $actual);

        $expected = [
            'My1' => 'Metadata1',
        ];
        $actual   = $message->getMetaData();
        $this->assertSame($expected, $actual);
    }
}
