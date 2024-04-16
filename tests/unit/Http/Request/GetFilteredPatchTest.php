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

namespace Phalcon\Tests\Unit\Http\Request;

use Phalcon\Tests\Unit\Http\AbstractHttpTestCase;
use Phalcon\Tests1\Fixtures\Page\Http;

use function file_put_contents;
use function restore_error_handler;
use function set_error_handler;

final class GetFilteredPatchTest extends AbstractHttpTestCase
{
    /**
     * Tests Phalcon\Http\Request :: getFilteredPatch()
     *
     * @issue  16188
     * @author Phalcon Team <team@phalcon.io>
     * @since  2022-11-01
     */
    public function testHttpRequestGetFilteredPatch()
    {
        $this->registerStream();
        set_error_handler(
            static function (): bool {
            return true;
        });
        file_put_contents(Http::STREAM, 'no-id=24');
        restore_error_handler();

        $_SERVER['REQUEST_METHOD'] = Http::METHOD_PATCH;

        $request = $this->getRequestObject();
        $request
            ->setParameterFilters('id', ['absint'], ['patch'])
        ;

        $expected = 24;
        $actual   = $request->getFilteredPut('id', 24);
        $this->assertSame($expected, $actual);

        $this->unregisterStream();
    }
}
