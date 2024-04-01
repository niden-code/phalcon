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

namespace Phalcon\Tests\Unit\Cache\Adapter\Redis;

use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Cache\Adapter\Redis;
use Phalcon\Cache\Exception as CacheException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

use function uniqid;

#[RequiresPhpExtension('redis')]
final class DecrementTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cache\Adapter\Redis :: decrement()
     *
     * @return void
     *
     * @throws CacheException
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterRedisDecrement(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Redis($serializer, self::getOptionsRedis());

        $key      = uniqid();
        $expected = 100;
        $actual   = $adapter->increment($key, 100);
        $this->assertSame($expected, $actual);

        $expected = 99;
        $actual   = $adapter->decrement($key);
        $this->assertSame($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        $expected = 90;
        $actual   = $adapter->decrement($key, 9);
        $this->assertEquals($expected, $actual);

        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        /**
         * unknown key
         */
        $key      = uniqid();
        $expected = -9;
        $actual   = $adapter->decrement($key, 9);
        $this->assertSame($expected, $actual);
    }
}
