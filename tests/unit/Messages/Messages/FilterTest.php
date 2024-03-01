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

final class FilterTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Messages\Messages :: filter()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessagesFilter(): void
    {
        $messages = new Messages(
            [
                new Message(
                    'Password: no number present',
                    'Password',
                    'MyType1',
                    111,
                    [
                        'My1' => 'Metadata1',
                    ]
                ),
                new Message(
                    'Password: no uppercase letter present',
                    'Password',
                    'MyType2',
                    222,
                    [
                        'My2' => 'Metadata2',
                    ]
                ),
                new Message(
                    'Email: not valid',
                    'Email',
                    'MyType3',
                    333,
                    [
                        'My3' => 'Metadata3',
                    ]
                ),
            ]
        );

        $this->assertCount(3, $messages);

        $actual = $messages->filter('Password');

        $this->assertIsArray($actual);

        $this->assertCount(2, $actual);

        /** @var Message $message1 */
        /** @var Message $message2 */
        [$message1, $message2] = $actual;

        $expected = 'Password: no number present';
        $actual   = $message1->getMessage();
        $this->assertSame($expected, $actual);

        $expected = 'Password';
        $actual   = $message1->getField();
        $this->assertSame($expected, $actual);

        $expected = 'Password';
        $actual   = $message1->getField();
        $this->assertSame($expected, $actual);

        $expected = 111;
        $actual   = $message1->getCode();
        $this->assertSame($expected, $actual);

        $expected = ['My1' => 'Metadata1'];
        $actual   = $message1->getMetaData();
        $this->assertSame($expected, $actual);


        $expected = 'Password: no uppercase letter present';
        $actual   = $message2->getMessage();
        $this->assertSame($expected, $actual);
        $expected = 'Password';
        $actual   = $message2->getField();
        $this->assertSame($expected, $actual);
        $expected = 'Password';
        $actual   = $message2->getField();
        $this->assertSame($expected, $actual);
        $expected = 222;
        $actual   = $message2->getCode();
        $this->assertSame($expected, $actual);
        $expected = ['My2' => 'Metadata2'];
        $actual   = $message2->getMetaData();
        $this->assertSame($expected, $actual);
    }
}
