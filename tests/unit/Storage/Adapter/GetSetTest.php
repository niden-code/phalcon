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

namespace Phalcon\Tests\Unit\Storage\Adapter;

use ArrayObject;
use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\RedisCluster;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Adapter\Weak;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Unit\Storage\AbstractStorageTestCase;
use SplObjectStorage;
use SplQueue;
use stdClass;

use function uniqid;

final class GetSetTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* :: get()/set()
     *
     * @dataProvider getGetSetExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetSet(
        string $extension,
        string $class,
        array $options,
        mixed $value
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key = uniqid('k-');

        $result = $adapter->set($key, $value);
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertTrue($result);

        $expected = $value;
        $actual = $adapter->get($key);
        $this->assertEquals($expected, $actual);

        /**
         * This will issue delete
         */
        $result = $adapter->set($key, $value, 0);
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertFalse($result);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: get()/set()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetSetWithZeroTtl(
        string $class,
        array $options,
        string $extension,
        string $name
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $class($serializer, $options);

        $key   = uniqid();
        $value = 'weak' === $name ? new stdClass() : 'test';

        $result = $adapter->set($key, $value);
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertTrue($result);

        /**
         * This will issue delete
         */
        $result = $adapter->set($key, $value, 0);
        $this->assertTrue($result);

        $result = $adapter->has($key);
        $this->assertFalse($result);
    }
}
