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

use function gethostbyname;

final class GetServerAddressTest extends AbstractHttpTestCase
{
    /**
     * Tests getServerAddress
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-04
     */
    public function testHttpRequestGetServerAddress()
    {
        $request = $this->getRequestObject();

        $_SERVER['SERVER_ADDR'] = Http::TEST_IP_ONE;

        $expected = Http::TEST_IP_ONE;
        $actual   = $request->getServerAddress();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests getServerAddress default
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-04
     */
    public function testHttpRequestGetServerAddressDefault()
    {
        $request = $this->getRequestObject();

        $expected = gethostbyname('localhost');
        $actual   = $request->getServerAddress();
        $this->assertSame($expected, $actual);
    }
}
