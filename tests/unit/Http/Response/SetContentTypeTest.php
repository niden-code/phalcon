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

namespace Phalcon\Tests\Unit\Http\Response;

use Phalcon\Tests\Unit\Http\AbstractHttpTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

final class SetContentTypeTest extends AbstractHttpTestCase
{
    /**
     * Tests the setContentType
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseSetContentType()
    {
        $response = $this->getResponseObject();
        $response->resetHeaders();

        $response->setContentType(Http::CONTENT_TYPE_JSON);

        $headers = $response->getHeaders();

        $expected = Http::CONTENT_TYPE_JSON;
        $actual   = $headers->get(Http::CONTENT_TYPE);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests the setContentType with charset
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseSetContentTypeWithCharset()
    {
        $response = $this->getResponseObject();
        $response->resetHeaders();

        $response->setContentType(Http::CONTENT_TYPE_JSON, Http::UTF8);

        $headers = $response->getHeaders();

        $expected = Http::CONTENT_TYPE_JSON_UTF8;
        $actual   = $headers->get(Http::CONTENT_TYPE);
        $this->assertSame($expected, $actual);
    }
}
