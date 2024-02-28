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
use PHPUnit\Framework\TestCase;

final class GetAttributesTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\ServerRequest :: getAttributes()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-11
     */
    public function testHttpMessageServerRequestGetAttributes()
    {
        $request = (new ServerRequest())
            ->withAttribute('one', 'two')
            ->withAttribute('three', 'four')
        ;

        $expected = [
            'one'   => 'two',
            'three' => 'four',
        ];

        $this->assertSame(
            $expected,
            $request->getAttributes()
        );
    }

    /**
     * Tests Phalcon\Http\Message\ServerRequest :: getAttributes() - empty
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-11
     */
    public function testHttpMessageServerRequestGetAttributesEmpty()
    {
        $request = new ServerRequest();

        $this->assertEmpty(
            $request->getAttributes()
        );
    }
}
