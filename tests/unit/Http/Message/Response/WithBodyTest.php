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
use Phalcon\Http\Message\Stream;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

final class WithBodyTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\Response :: withBody()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-03-09
     */
    public function testHttpMessageResponseWithBody()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $fileName = dataDir2('/assets/stream/mit.txt');
        $stream   = new Stream($fileName, 'rb');
        $response = new Response();

        $newInstance = $response->withBody($stream);

        $this->assertNotSame($response, $newInstance);

        $content = file_get_contents($fileName);

        $this->assertSame($content, (string)$newInstance->getBody());
    }
}
