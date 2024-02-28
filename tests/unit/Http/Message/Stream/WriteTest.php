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
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function getNewFileName2;
use function logsDir2;

final class WriteTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\Stream :: write()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageStreamWrite()
    {
        $fileName = getNewFileName2();
        $fileName = logsDir2($fileName);
        $stream   = new Stream($fileName, 'wb');

        $source   = 'A well regulated Militia, being necessary to the security of a free State, '
            . 'the right of the people to keep and bear Arms, shall not be infringed.';
        $expected = strlen($source);
        $actual   = $stream->write($source);
        $this->assertSame($expected, $actual);

        $stream->close();

        $stream   = new Stream($fileName, 'rb');
        $expected = $source;
        $actual   = $stream->getContents();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Http\Message\Stream :: write() - detached
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageStreamWriteDetached()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('A valid resource is required.');

        $fileName = getNewFileName2();
        $fileName = logsDir2($fileName);
        $stream   = new Stream($fileName, 'wb');
        $stream->detach();

        $stream->write('abc');
    }

    /**
     * Tests Phalcon\Http\Message\Stream :: write() - exception not writable
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageStreamWriteNotWritable()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The resource is not writable.');

        $fileName = getNewFileName2();
        $fileName = logsDir2($fileName);
        $stream   = new StreamFixture($fileName, 'wb');

        $stream->write('abc');
    }
}
