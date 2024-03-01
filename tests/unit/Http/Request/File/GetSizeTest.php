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
use Phalcon\Tests1\Fixtures\Page\Http;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function dataDir2;

final class GetSizeTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Http\Request\File :: getSize()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestFileGetSize()
    {
        $file = new File(
            [
                'name'     => 'test',
                'type'     => Http::CONTENT_TYPE_PLAIN,
                'tmp_name' => self::dataDir('/assets/images/example-jpg.jpg'),
                'size'     => 1,
                'error'    => 0,
            ]
        );

        $expected = 1;
        $actual   = $file->getSize();
        $this->assertSame($expected, $actual);
    }
}
