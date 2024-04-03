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
use Phalcon\Session\Adapter\Stream as SessionStream;
use Phalcon\Session\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Session\Adapter\Libmemcached;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests1\Fixtures\Session\StreamIniGetFixture;
use Phalcon\Tests1\Fixtures\Session\StreamWriteableFixture;
use Phalcon\Tests1\Fixtures\Traits\DiTrait2;
use SessionHandlerInterface;

use function ini_get;

final class ConstructTest extends AbstractUnitTestCase
{
    use DiTrait2;

    /**
     * Tests Phalcon\Session\Adapter\Libmemcached :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterLibmemcachedConstruct(): void
    {
        $adapter = $this->newService('sessionLibmemcached');

        $class = SessionHandlerInterface::class;
        $this->assertInstanceOf($class, $adapter);
    }

    /**
     * Tests Phalcon\Session\Adapter\Libmemcached :: __construct() - with custom prefix
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-23
     */
    public function testSessionAdapterLibmemcachedConstructWithPrefix(): void
    {
        $options           = self::getOptionsLibmemcached();
        $options['prefix'] = 'my-custom-prefix-';

        $serializerFactory = new SerializerFactory();
        $factory           = new AdapterFactory($serializerFactory);

        $memcachedSession = new Libmemcached($factory, $options);

        $actual = $memcachedSession->write(
            'my-session-prefixed-key',
            'test-data'
        );

        $this->assertTrue($actual);

        $memcachedStorage = $factory->newInstance('libmemcached', $options);

        $expected = 'my-custom-prefix-';
        $actual   = $memcachedStorage->getPrefix();
        $this->assertEquals($expected, $actual);

        $expected = 'test-data';
        $actual   = $memcachedStorage->get('my-session-prefixed-key');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Phalcon\Session\Adapter\Noop :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterNoopConstruct(): void
    {
        $adapter = $this->newService('sessionNoop');

        $class = SessionHandlerInterface::class;
        $this->assertInstanceOf($class, $adapter);
    }

    /**
     * Tests Phalcon\Session\Adapter\Redis :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterRedisConstruct(): void
    {
        $adapter = $this->newService('sessionRedis');

        $class = SessionHandlerInterface::class;
        $this->assertInstanceOf($class, $adapter);
    }

    /**
     * Tests Phalcon\Session\Adapter\Redis :: __construct() - with custom prefix
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-23
     */
    public function testSessionAdapterRedisConstructWithPrefix(): void
    {
        $options           = self::getOptionsRedis();
        $options['prefix'] = 'my-custom-prefix-';

        $serializerFactory = new SerializerFactory();
        $factory           = new AdapterFactory($serializerFactory);

        $redisSession = new Redis($factory, $options);

        $actual = $redisSession->write(
            'my-session-prefixed-key',
            'test-data'
        );

        $this->assertTrue($actual);

        $redisStorage = $factory->newInstance('redis', $options);

        $expected = 'my-custom-prefix-';
        $actual   = $redisStorage->getPrefix();
        $this->assertEquals($expected, $actual);

        $expected = 'test-data';
        $actual   = $redisStorage->get('my-session-prefixed-key');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Phalcon\Session\Adapter\Stream :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamConstruct(): void
    {
        $adapter = $this->newService('sessionStream');
        $class   = SessionHandlerInterface::class;
        $this->assertInstanceOf($class, $adapter);
    }

    /**
     * Tests Phalcon\Session\Adapter\Stream :: __construct() - empty session_path
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamConstructEmptySessionPath(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The session save path cannot be empty');

        $adapter = new StreamIniGetFixture();
    }

    /**
     * Tests Phalcon\Session\Adapter\Stream :: __construct() - not writable path
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionAdapterStreamConstructNotWritablePath(): void
    {
        $path = ini_get('session.save_path');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The session save path [' . $path . '] is not writable'
        );

        $adapter = new StreamWriteableFixture();
    }
}
