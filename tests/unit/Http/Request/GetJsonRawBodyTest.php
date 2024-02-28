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

use function file_put_contents;
use function json_encode;

final class GetJsonRawBodyTest extends AbstractHttpTestCase
{
    /**
     * Tests Phalcon\Http\Request :: getJsonRawBody()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetJsonRawBody()
    {
        $this->registerStream();

        $input = json_encode(
            [
                'fruit'    => 'orange',
                'quantity' => '4',
            ]
        );

        file_put_contents(Http::STREAM, $input);

        $request = $this->getRequestObject();

        $expected = json_decode($input, true);
        $actual   = $request->getJsonRawBody(true);
        $this->assertSame($expected, $actual);

        $this->unregisterStream();
    }

    /**
     * Tests Phalcon\Http\Request :: getJsonRawBody() - empty
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetJsonRawBodyEmpty()
    {
        // Empty
        $request = $this->getRequestObject();
        $actual  = $request->getRawBody();
        $this->assertEmpty($actual);
    }
}
