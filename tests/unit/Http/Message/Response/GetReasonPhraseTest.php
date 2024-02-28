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

use Phalcon\Http\Message\Response;
use Phalcon\Tests1\Fixtures\Page\Http;
use PHPUnit\Framework\TestCase;

final class GetReasonPhraseTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\Response :: getReasonPhrase()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-03-09
     */
    public function testHttpMessageResponseGetReasonPhrase()
    {
        $response = new Response();

        $this->assertSame(
            'OK',
            $response->getReasonPhrase()
        );
    }

    /**
     * Tests Phalcon\Http\Message\Response :: getReasonPhrase() - other port
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-03-09
     */
    public function testHttpMessageResponseGetReasonPhraseOtherPort()
    {
        $response = new Response(Http::STREAM_MEMORY, 420);

        $this->assertSame(
            'Method Failure',
            $response->getReasonPhrase()
        );
    }
}
