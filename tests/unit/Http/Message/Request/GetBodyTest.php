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

use Phalcon\Http\Message\Request;
use Phalcon\Http\Message\Stream;
use PHPUnit\Framework\TestCase;

final class GetBodyTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\Request :: getBody()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageRequestGetBody()
    {
        $fileName = dataDir2('/assets/stream/mit.txt');

        $stream = new Stream($fileName, 'rb');

        $request = new Request('GET', null, $stream);

        $this->assertStringEqualsFile($fileName, (string)$request->getBody());
    }

    /**
     * Tests Phalcon\Http\Message\Request :: getBody() - empty
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageRequestGetBodyEmpty()
    {
        $request = new Request();

        $this->assertInstanceOf(Stream::class, $request->getBody());
    }
}
