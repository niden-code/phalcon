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

namespace Phalcon\Tests\Unit\Http\Message\ServerRequest;

use Phalcon\Http\Message\ServerRequest;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

final class GetCookieParamsTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Http\Message\ServerRequest :: getCookieParams()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageServerRequestGetCookieParams()
    {
        $cookies = ['one' => 'two'];
        $request = new ServerRequest('GET', null, [], Http::STREAM, [], $cookies);

        $expected = $cookies;
        $actual   = $request->getCookieParams();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Http\Message\ServerRequest :: getCookieParams() - empty
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageServerRequestGetCookieParamsEmpty()
    {
        $request = new ServerRequest();

        $actual = $request->getCookieParams();
        $this->assertEmpty($actual);
    }
}
