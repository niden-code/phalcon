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

class OffsetExistsTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Messages\Messages :: offsetExists()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessagesOffsetExists(): void
    {
        $messages = new Messages(
            [
                1 => new Message(
                    'This is a message #1',
                    'MyField1',
                    'MyType1',
                    111
                ),
                2 => new Message(
                    'This is a message #2',
                    'MyField2',
                    'MyType2',
                    222
                ),
            ]
        );


        $actual = $messages->offsetExists(0);
        $this->assertFalse($actual);

        $actual = $messages->offsetExists(1);
        $this->assertTrue($actual);

        $actual = $messages->offsetExists(2);
        $this->assertTrue($actual);
    }
}
