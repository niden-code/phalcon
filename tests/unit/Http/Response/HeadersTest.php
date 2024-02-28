<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Tests\Unit\Http\Response;

use Phalcon\Events\Event;
use Phalcon\Http\Response\Headers;
use Phalcon\Http\Response\HeadersInterface;
use Phalcon\Tests\Unit\Http\AbstractHttpTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

use function getProtectedProperty;
use function setProtectedProperty2;
use function uniqid;

final class HeadersTest extends AbstractHttpTestCase
{
    /**
     * Tests the get of the response status headers
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/12895
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2017-06-17
     *
     * @dataProvider statusHeaderProvider
     */
    public function testHttpResponseGetResponseStatusHeader(
        string $code
    ) {
        $headers = new Headers();

        setProtectedProperty2(
            $headers,
            'headers',
            [
                Http::STATUS => $code,
            ]
        );

        $expected = $code;
        $actual   = $headers->get(Http::STATUS);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests the set of the response status headers
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/12895
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2017-06-17
     *
     * @dataProvider statusHeaderProvider
     */
    public function testHttpResponseSetResponseStatusHeader(
        string $code
    ) {
        $headers = new Headers();

        $headers->set(Http::STATUS, $code);
        $headers = getProtectedProperty($headers, 'headers');

        $expected = 1;
        $this->assertCount($expected, $headers);

        $actual = isset($headers[Http::STATUS]);
        $this->assertTrue($actual);

        $expected = (string)$code;
        $actual   = $headers[Http::STATUS];
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public static function statusHeaderProvider(): array
    {
        return [
            [
                '422',
            ],

            [
                '301',
            ],

            [
                '200',
            ],

            [
                '503',
            ],

            [
                '404',
            ],

            [
                '201',
            ],

            [
                '100',
            ],
        ];
    }

    /**
     * Test the event response:beforeSendHeaders
     *
     * @author Cameron Hall <me@chall.id.au>
     * @since  2018-11-28
     */
    public function testEventAfterSendHeaders()
    {
        $eventsManager = $this->getDI()->getShared('eventsManager');

        $eventsManager->attach(
            'response:afterSendHeaders',
            function (Event $event, $response) {
                echo 'some content';
            }
        );

        $response = $this->getResponseObject();

        ob_start();

        $response->setEventsManager($eventsManager);
        $response->sendHeaders();

        $expected = 'some content';
        $actual   = ob_get_clean();
        $this->assertSame($expected, $actual);
    }

    /**
     * Test the event response:beforeSendHeaders
     *
     * @author Cameron Hall <me@chall.id.au>
     * @since  2018-11-28
     */
    public function testEventBeforeSendHeaders()
    {
        $this->setNewFactoryDefault();

        $eventsManager = $this->container->getShared('eventsManager');

        $eventsManager->attach(
            'response:beforeSendHeaders',
            function (Event $event, $response) {
                return false;
            }
        );

        $response = $this->getResponseObject();
        $response->setEventsManager($eventsManager);

        $actual = $response->sendHeaders();
        $this->assertFalse($actual);
    }

    /**
     * Tests the get and set of the response headers
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-05
     */
    public function testHttpResponseHeadersGetSet()
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML
        );

        $expected = Http::CONTENT_TYPE_HTML;
        $actual   = $headers->get(Http::CONTENT_TYPE);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests the has of the response headers
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-02
     */
    public function testHttpResponseHeadersHas()
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML
        );

        $actual = $headers->has(Http::CONTENT_TYPE);
        $this->assertTrue($actual);

        $header = uniqid('header-');
        $actual = $headers->has($header);
        $this->assertFalse($actual);
    }

    /**
     * Tests the instance of the object
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-05
     */
    public function testHttpResponseHeadersInstanceOf()
    {
        $headers = new Headers();

        $class = Headers::class;
        $this->assertInstanceOf($class, $headers);

        $class = HeadersInterface::class;
        $this->assertInstanceOf($class, $headers);
    }

    /**
     * Tests setting a raw response header
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-05
     */
    public function testHttpResponseHeadersRaw()
    {
        $headers = new Headers();

        $headers->setRaw(Http::CONTENT_TYPE_HTML_RAW);

        $actual = $headers->get(Http::CONTENT_TYPE);
        $this->assertEmpty($actual);
    }

    /**
     * Tests removing a response header
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-05
     */
    public function testHttpResponseHeadersRemove()
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML
        );

        $headers->remove(Http::CONTENT_TYPE);

        $actual = $headers->get(Http::CONTENT_TYPE);
        $this->assertEmpty($actual);
    }

    /**
     * Tests resetting the response headers
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-05
     */
    public function testHttpResponseHeadersReset()
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML
        );

        $headers->reset();

        $actual = $headers->get(Http::CONTENT_TYPE);
        $this->assertEmpty($actual);
    }

    /**
     * Tests toArray in response headers
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-05
     */
    public function testHttpResponseHeadersToArray()
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML
        );

        $expected = [
            Http::CONTENT_TYPE => Http::CONTENT_TYPE_HTML,
        ];

        $actual = $headers->toArray();
        $this->assertSame($expected, $actual);
    }
}
