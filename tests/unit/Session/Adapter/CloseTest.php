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

namespace Phalcon\Tests\Unit\Session\Adapter;

use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;

final class CloseTest extends AbstractUnitTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Session\Adapter\Libmemcached :: close()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterLibmemcachedClose(
        string $name
    ): void {
        $adapter = $this->newService($name);
        $actual  = $adapter->close();
        $this->assertTrue($actual);
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            ['sessionLibmemcached'],
            ['sessionNoop'],
            ['sessionRedis'],
            ['sessionStream'],
        ];
    }
}
