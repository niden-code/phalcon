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

namespace Phalcon\Tests\Unit\Http\Request\File;

use Phalcon\Http\Request\File;
use Phalcon\Tests\Fixtures\Page\Http;
use Phalcon\Tests\AbstractUnitTestCase;

use function dataDir;

final class GetExtensionTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Http\Request\File :: getExtension()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestFileGetExtension(): void
    {
        $file = new File(
            [
                'name'      => 'test.php',
                'type'      => Http::CONTENT_TYPE_PLAIN,
                'extension' => 'jpg',
                'tmp_name'  => dataDir('/assets/images/example-jpg.jpg'),
                'size'      => 1,
                'error'     => 0,
            ]
        );

        $expected = Http::STREAM_NAME;
        $actual   = $file->getExtension();
        $this->assertSame($expected, $actual);
    }
}
