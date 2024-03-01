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

namespace Phalcon\Tests\Unit\Http\Message\ServerRequest;

use Phalcon\Http\Message\ServerRequest;
use Phalcon\Http\Message\Stream;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function file_get_contents;

final class GetBodyTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Http\Message\ServerRequest :: getBody()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageServerRequestGetBody()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $fileName = self::dataDir('/assets/stream/mit.txt');

        $stream  = new Stream($fileName, 'rb');
        $request = new ServerRequest('GET', null, [], $stream);

        $content = file_get_contents($fileName);

        $this->assertSame($content, (string)$request->getBody());
    }

    /**
     * Tests Phalcon\Http\Message\ServerRequest :: getBody() - empty
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageServerRequestGetBodyEmpty()
    {
        $request = new ServerRequest();

        $this->assertInstanceOf(
            Stream\Input::class,
            $request->getBody()
        );
    }
}
