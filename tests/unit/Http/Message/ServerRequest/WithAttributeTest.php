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

final class WithAttributeTest extends TestCase
{
    /**
     * Tests Phalcon\Http\Message\ServerRequest :: withAttribute()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-10
     */
    public function testHttpMessageServerRequestWithAttribute()
    {
        $request     = new ServerRequest();
        $newInstance = $request->withAttribute('one', 'two');

        $this->assertNotSame($request, $newInstance);
        $this->assertSame('two', $newInstance->getAttribute('one'));
    }
}
