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

namespace Phalcon\Tests\Unit\Messages\Message;

use Phalcon\Messages\Message;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Messages\Message :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessageConstruct(): void
    {
        $message = new Message(
            'This is a message #1',
            'MyField',
            'MyType',
            111,
            [
                'My1' => 'Metadata1',
            ]
        );

        $expected = 'This is a message #1';
        $actual   = $message->getMessage();
        $this->assertSame($expected, $actual);

        $expected = 'MyField';
        $actual   = $message->getField();
        $this->assertSame($expected, $actual);

        $expected = 'MyType';
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

    /**
     * Tests Phalcon\Messages\Message :: __construct() - chain
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testMessagesMessageConstructChain(): void
    {
        $message = new Message('This is a message #1');

        $message
            ->setField('MyField')
            ->setType('MyType')
            ->setCode(111)
            ->setMetaData(
                [
                    'My1' => 'Metadata1',
                ]
            )
        ;

        $expected = 'This is a message #1';
        $actual   = $message->getMessage();
        $this->assertSame($expected, $actual);

        $message->setMessage('This is a message #2');

        $expected = 'This is a message #2';
        $actual   = $message->getMessage();
        $this->assertSame($expected, $actual);

        $expected = 'MyField';
        $actual   = $message->getField();
        $this->assertSame($expected, $actual);

        $expected = 'MyType';
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
