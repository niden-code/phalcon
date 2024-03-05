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

namespace Phalcon\Tests\Unit\Http\Message\Request;

use Phalcon\Http\Message\Exception\InvalidArgumentException;
use Phalcon\Http\Message\Request;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

final class WithHeaderTest extends AbstractUnitTestCase
{
    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        return [
            [true],
            ["some \r\n"],
            ["some \r"],
            ["some \n"],
        ];
    }

    /**
     * Tests Phalcon\Http\Message\Request :: withHeader()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageRequestWithHeader()
    {
        $data = [
            'Accept' => [Http::CONTENT_TYPE_HTML],
        ];

        $request = new Request('GET', null, Http::STREAM_MEMORY, $data);

        $newInstance = $request->withHeader(
            'Cache-Control',
            [
                'max-age=0',
            ]
        );

        $this->assertNotSame($request, $newInstance);

        $expected = [
            'Accept' => [Http::CONTENT_TYPE_HTML],
        ];

        $this->assertSame(
            $expected,
            $request->getHeaders()
        );

        $expected = [
            'Accept' => [Http::CONTENT_TYPE_HTML],
            'Cache-Control' => ['max-age=0'],
        ];

        $this->assertSame(
            $expected,
            $newInstance->getHeaders()
        );
    }

    /**
     * Tests Phalcon\Http\Message\Request :: withHeader() - exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageRequestWithHeaderException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid header name Cache Control');

        $request = new Request();

        $newInstance = $request->withHeader(
            'Cache Control',
            [
                'max-age=0',
            ]
        );
    }

    /**
     * Tests Phalcon\Http\Message\Request :: withHeader() - exception value
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-02-10
     */
    public function testHttpMessageRequestWithHeaderExceptionValue(
        mixed $header
    ) {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid header value');

        $request = new Request();

        $newInstance = $request->withHeader(
            'Cache-Control',
            [
                $header,
            ]
        );
    }
}
