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

use Phalcon\Http\Request;
use Phalcon\Tests\Unit\Http\AbstractHttpTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

final class GetContentTypeTest extends AbstractHttpTestCase
{
    /**
     * Tests Phalcon\Http\Request :: getContentType()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetContentType()
    {
        $_SERVER['CONTENT_TYPE'] = Http::CONTENT_TYPE_XHTML_XML;

        $request = new Request();

        $expected = Http::CONTENT_TYPE_XHTML_XML;
        $actual   = $request->getContentType();
        $this->assertSame($expected, $actual);
    }
}
