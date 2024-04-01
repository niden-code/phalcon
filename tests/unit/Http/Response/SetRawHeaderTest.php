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

final class SetRawHeaderTest extends AbstractHttpTestCase
{
    /**
     * Tests the setRawHeader
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseSetRawHeader()
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();
        $response->setRawHeader(Http::HTTP_404_NOT_FOUND);

        $actual = $response->getHeaders();

        $actual   = $actual->get(Http::HTTP_304_NOT_MODIFIED);
        $this->assertFalse($actual);
    }
}
