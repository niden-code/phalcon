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

namespace Phalcon\Tests\Unit\Cache\Adapter\Memory;

use Phalcon\Cache\Adapter\Memory;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use stdClass;

final class GetSetTest extends AbstractUnitTestCase
{
    public static function providerExamples(): array
    {
        return [
            [
                'random string',
            ],
            [
                123456,
            ],
            [
                123.456,
            ],
            [
                true,
            ],
            [
                new stdClass(),
            ],
        ];
    }

    /**
     * Tests Phalcon\Cache\Adapter\Memory :: get()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterMemoryGetSet(
        mixed $value
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new Memory($serializer);

        $key = uniqid();

        $result = $adapter->set($key, $value);
        $this->assertTrue($result);

        $expected = $value;
        $actual   = $adapter->get($key);
        $this->assertEquals($expected, $actual);
    }
}
