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
use Phalcon\Tests1\Fixtures\Traits\MemcachedTrait;
use Phalcon\Tests1\Fixtures\Traits\RedisTrait;

use function cacheDir;
use function file_put_contents;
use function serialize;
use function uniqid;

final class DestroyTest extends AbstractUnitTestCase
{
    use DiTrait2;
    use MemcachedTrait;
    use RedisTrait;


    /**
     * Auto setup tests that need memcached
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->memcachedSetup();
        $this->redisSetup();
    }

    /**
     * Auto tear down tests that need memcached
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->memcachedTearDown();
        $this->redisTearDown();
    }

    /**
     * Tests Phalcon\Session\Adapter\Libmemcached :: destroy()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterLibmemcachedDestroy(): void
    {
        $adapter = $this->newService('sessionLibmemcached');

        $value  = uniqid();
        $key    = 'sess-memc-test1';
        $actual = serialize($value);

        $this->memcachedSet($key, $actual, 0);

        $actual = serialize($value);
        $this->memcachedHas($key, $actual);

        $actual = $adapter->destroy('test1');
        $this->assertTrue($actual);

        $this->memcachedNotHas($key);
    }

    /**
     * Tests Phalcon\Session\Adapter\Noop :: destroy()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterNoopDestroy(
        string $name
    ): void {
        $adapter = $this->newService($name);
        $actual = $adapter->destroy('test1');
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Session\Adapter\Redis :: destroy()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterRedisDestroy(): void
    {
        $adapter = $this->newService('sessionRedis');

        $value = uniqid();

        $this->redisSet(
            'string',
            'sess-reds-test1',
            serialize($value)
        );

        $actual = $adapter->destroy('test1');
        $this->assertTrue($actual);

        $this->redisNotHas('sess-reds-test1');
    }

    /**
     * Tests Phalcon\Session\Adapter\Stream :: destroy()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testSessionAdapterStreamDestroy(): void
    {
        $adapter = $this->newService('sessionStream');

        /**
         * Create a file in the session folder
         */
        file_put_contents(
            self::cacheDir('sessions/test1'),
            uniqid()
        );

        $actual = $adapter->destroy('test1');
        $this->assertTrue($actual);

        $file = self::cacheDir('sessions') . '/test1';
        $this->assertFileDoesNotExist($file);
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
