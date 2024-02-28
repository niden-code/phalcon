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

namespace Phalcon\Tests\Unit\Http\Request;

use Phalcon\Tests\Unit\Http\AbstractHttpTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

final class GetUserAgentTest extends AbstractHttpTestCase
{
    /**
     * Tests Phalcon\Http\Request :: getUserAgent()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetUserAgent()
    {
        $_SERVER['HTTP_USER_AGENT'] = Http::TEST_USER_AGENT;

        $request = $this->getRequestObject();

        $expected = Http::TEST_USER_AGENT;
        $actual   = $request->getUserAgent();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Http\Request :: getUserAgent() - default
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetUserAgentDefault()
    {
        $request = $this->getRequestObject();

        $actual = $request->getUserAgent();
        $this->assertEmpty($actual);
    }
}
