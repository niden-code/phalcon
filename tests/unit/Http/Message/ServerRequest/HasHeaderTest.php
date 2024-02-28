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
use Phalcon\Tests1\Fixtures\Page\Http;
use PHPUnit\Framework\TestCase;

final class HasHeaderTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\ServerRequest :: hasHeader()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageServerRequestHasHeader()
    {
        $data    = [
            'Accept' => [
                Http::CONTENT_TYPE_HTML,
                'text/json',
            ],
        ];
        $request = new ServerRequest('GET', null, [], Http::STREAM, $data);

        $actual = $request->hasHeader('accept');
        $this->assertTrue($actual);

        $actual = $request->hasHeader('aCCepT');
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Http\Message\ServerRequest :: hasHeader() - empty
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageServerRequestHasHeaderEmpty()
    {
        $request = new ServerRequest();

        $actual = $request->hasHeader('empty');
        $this->assertFalse($actual);
    }
}
