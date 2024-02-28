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

namespace Phalcon\Tests\Unit\Http\Message\Stream;

use Phalcon\Http\Message\Stream;
use Phalcon\Tests\Fixtures\Http\Message\StreamFixture;
use Phalcon\Tests1\Fixtures\Page\Http;
use PHPUnit\Framework\TestCase;

use function dataDir2;

final class GetSizeTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\Stream :: getSize()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageStreamGetSize()
    {
        $fileName = dataDir2('assets/stream/mit.txt');
        $expected = filesize($fileName);
        $stream   = new Stream($fileName, 'rb');
        $actual   = $stream->getSize();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Http\Message\Stream :: getSize() - invalid stream
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageStreamGetSizeInvalid()
    {
        $stream   = new Stream(Http::STREAM_MEMORY, 'rb');
        $expected = 0;
        $actual   = $stream->getSize();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Http\Message\Stream :: getSize() - invalid handle
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageStreamGetSizeInvalidHandle()
    {
        $stream = new StreamFixture(Http::STREAM_MEMORY, 'rb');
        $stream->setHandle(null);

        $actual = $stream->getSize();
        $this->assertNull($actual);
    }
}
