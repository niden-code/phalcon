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
use PHPUnit\Framework\TestCase;

final class GetProtocolVersionTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\Response :: getProtocolVersion()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-03-05
     */
    public function testHttpMessageResponseGetProtocolVersion()
    {
        $response = new Response();

        $this->assertSame(
            '1.1',
            $response->getProtocolVersion()
        );
    }
}
