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

namespace Phalcon\Tests\Unit\Http\Message\Response;

use InvalidArgumentException;
use Phalcon\Http\Message\Response;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

final class GetStatusCodeTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Http\Message\Response :: getStatusCode()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-03-09
     */
    public function testHttpMessageResponseGetStatusCode()
    {
        $response = new Response();

        $this->assertSame(
            200,
            $response->getStatusCode()
        );
    }

    /**
     * Tests Phalcon\Http\Message\Response :: getStatusCode() - exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-03-09
     */
    public function testHttpMessageResponseGetStatusCodeException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Invalid status code '847', (allowed values 100-599)"
        );

        $response = new Response(Http::STREAM_MEMORY, 847);
    }
}
