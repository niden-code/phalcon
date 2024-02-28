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

use function file_get_contents;

final class WithBodyTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\Request :: withBody()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageRequestWithBody()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $fileName = dataDir2('/assets/stream/mit.txt');

        $stream = new Stream($fileName, 'rb');

        $request = new Request();

        $newInstance = $request->withBody($stream);

        $this->assertNotSame($request, $newInstance);

        $content = file_get_contents($fileName);

        $this->assertSame($content, (string)$newInstance->getBody());
    }
}
