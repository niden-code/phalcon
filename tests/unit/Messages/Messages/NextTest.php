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

final class NextTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Messages\Messages :: next()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessagesNext(): void
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

        $messages->next();

        $message = $messages->current();

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
