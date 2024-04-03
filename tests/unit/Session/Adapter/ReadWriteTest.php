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

use Codeception\Stub;
use Phalcon\Session\Adapter\Redis;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Session\StreamGetContentsFixture;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;
use Phalcon\Tests1\Fixtures\Traits\MemcachedTrait;
use Phalcon\Tests1\Fixtures\Traits\RedisTrait;

use function file_get_contents;
use function uniqid;

final class ReadWriteTest extends AbstractUnitTestCase
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
     * Tests Phalcon\Session\Adapter\Libmemcached :: write()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterLibmemcachedRead(): void
    {
        $adapter = $this->newService('sessionLibmemcached');

        $value = uniqid();

        $adapter->write('test1', $value);

        $actual = $adapter->read('test1');
        $this->assertEquals($value, $actual);

        $this->memcachedClear();

        $actual = $adapter->read('test1');
        $this->assertNotNull($actual);
    }

    /**
     * Tests Phalcon\Session\Adapter\Noop :: write()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterNoopRead(): void
    {
        $adapter = $this->newService('sessionNoop');
        $value   = uniqid();

        $adapter->write('test1', $value);

        $expected = '';
        $actual   = $adapter->read('test1');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Phalcon\Session\Adapter\Redis :: read()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterRedisRead(): void
    {
        /** @var Redis $adapter */
        $adapter = $this->newService('sessionRedis');
        $value   = uniqid();

        $adapter->write('test1', $value);

        $expected = $value;
        $actual   = $adapter->read('test1');
        $this->assertEquals($expected, $actual);
        $this->redisCommand('del', 'sess-reds-test1');

        $actual = $adapter->read('test1');
        $this->assertNotNull($actual);
    }

    /**
     * Tests Phalcon\Session\Adapter\Stream :: read()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamRead(): void
    {
        $adapter = $this->newService('sessionStream');

        $value = uniqid();
        $adapter->write('test1', $value);

        $actual = $adapter->read('test1');
        $this->assertEquals($value, $actual);

        self::safeDeleteFile(self::cacheDir('sessions/test1'));
    }

    /**
     * Tests Phalcon\Session\Adapter\Stream :: read() - no data
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamReadNoData(): void
    {
        $adapter = $this->newService('sessionStream');

        $value = uniqid();
        $adapter->write('test1', $value);

        $mock   = new StreamGetContentsFixture();
        $actual = $mock->read('test1');
        $this->assertEmpty($actual);

        self::safeDeleteFile(self::cacheDir('sessions/test1'));
    }

    /**
     * Tests Phalcon\Session\Adapter\Stream :: write()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamWrite(): void
    {
        $adapter = $this->newService('sessionStream');
        $value   = uniqid();
        $adapter->write('test1', $value);
        $file = self::cacheDir('sessions') . '/test1';
        $this->assertFileExists($file);

        $contents = file_get_contents($file);
        $this->assertStringContainsString($value, $contents);
        self::safeDeleteFile(self::cacheDir('sessions/test1'));
    }
}
