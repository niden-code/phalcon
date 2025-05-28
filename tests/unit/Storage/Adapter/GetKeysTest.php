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

use Phalcon\Storage\Adapter\AdapterInterface;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\Unit\Storage\AbstractStorageTestCase;
use stdClass;

use function outputDir;
use function uniqid;

final class GetKeysTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* :: getKeys()
     *
     * @dataProvider getExamples
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetKeys(
        string $adapterClass,
        array $options,
        string $extension,
        string $name
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);

        if ('redis' === $name) {
            $adapter->getAdapter()->flushAll();
        } elseif ('rediscluster' === $name) {
            foreach ($adapter->getAdapter()->_masters() as $master) {
                $adapter->getAdapter()->flushAll($master);
            }
        } else {
            $adapter->clear();
        }

        $keys = $this->setupTest($adapter, $name);

        $this->runTests($adapter, $keys);

        if ('stream' === $name) {
            $this->safeDeleteDirectory(outputDir('ph-strm-'));
        }
    }

//    /**
//     * Tests Phalcon\Storage\Adapter\Libmemcached :: getKeys()
//     *
//     * @return void
//     *
//     * @throws HelperException
//     * @throws StorageException
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2020-09-09
//     */
//    public function testStorageAdapterLibmemcachedGetKeys(): void
//    {
//        $this->checkExtensionIsLoaded('memcached');
//
//        $serializer = new SerializerFactory();
//        $adapter    = new Libmemcached(
//            $serializer,
//            getOptionsLibmemcached()
//        );
//
//        $memcachedServerVersions   = $adapter->getAdapter()
//                                             ->getVersion()
//        ;
//        $memcachedExtensionVersion = phpversion('memcached');
//
//        foreach ($memcachedServerVersions as $memcachedServerVersion) {
//            // https://www.php.net/manual/en/memcached.getallkeys.php#123793
//            // https://bugs.launchpad.net/libmemcached/+bug/1534062
//            if (
//                version_compare($memcachedServerVersion, '1.4.23', '>=') &&
//                version_compare($memcachedExtensionVersion, '3.0.1', '<')
//            ) {
//                $this->markTestSkipped(
//                    'getAllKeys() does not work in certain Memcached versions'
//                );
//            }
//
//            // https://github.com/php-memcached-dev/php-memcached/issues/367
//            if (version_compare($memcachedServerVersion, '1.5.0', '>=')) {
//                $this->markTestSkipped(
//                    'getAllKeys() does not work in certain Memcached versions'
//                );
//            }
//        }
//
//        $this->assertTrue($adapter->clear());
//
//        $this->runTests($adapter, 'ph-memc-');
//    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: getKeys()
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author ekmst <https://github.com/ekmst>
     * @since  2020-09-09
     * @issue  cphalcon/#14190
     */
    public function testStorageAdapterStreamGetKeysIssue14190(): void
    {
        $prefix     = 'basePrefix-';
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => outputDir(),
            ]
        );

        $adapter->clear();

        $key1   = $prefix . uniqid();
        $key2   = $prefix . uniqid();
        $actual = $adapter->set($key1, 'test');
        $this->assertTrue($actual);
        $actual = $adapter->set($key2, 'test');
        $this->assertTrue($actual);

        $expected = [
            $key1,
            $key2,
        ];
        sort($expected);

        $actual = $adapter->getKeys($prefix);
        sort($actual);

        $this->assertSame($expected, $actual);

        foreach ($expected as $key) {
            $actual = $adapter->delete($key);
            $this->assertTrue($actual);
        }

        $this->safeDeleteDirectory(outputDir('basePrefix-'));
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: getKeys()
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author ekmst <https://github.com/ekmst>
     * @since  2020-09-09
     * @issue  cphalcon/#14190
     */
    public function testStorageAdapterStreamGetKeysPrefix(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => outputDir(),
                'prefix'     => 'pref-',
            ]
        );

        $actual = $adapter->clear();
        $this->assertTrue($actual);
        $actual = $adapter->getKeys();
        $this->assertEmpty($actual);

        $actual = $adapter->set('key', 'test');
        $this->assertNotFalse($actual);
        $actual = $adapter->set('key1', 'test');
        $this->assertNotFalse($actual);
        $actual = $adapter->set('somekey', 'test');
        $this->assertNotFalse($actual);
        $actual = $adapter->set('somekey1', 'test');
        $this->assertNotFalse($actual);

        $expected = [
            'key',
            'key1',
            'somekey',
            'somekey1',
        ];
        $actual   = $adapter->getKeys();
        sort($actual);
        $this->assertSame($expected, $actual);

        $expected = [
            'somekey',
            'somekey1',
        ];

        $actual = $adapter->getKeys('so');
        sort($actual);
        $this->assertSame($expected, $actual);

        $actual = $adapter->clear();
        $this->assertTrue($actual);

        $this->safeDeleteDirectory(outputDir('pref-'));
    }

    /**
     * @param AdapterInterface $adapter
     * @param string           $name
     * @param string           $prefix
     *
     * @return void
     */
    private function runTests(
        AdapterInterface $adapter,
        array $keys
    ): void {
        [$key1, $key2, $key3, $key4] = $keys;

        $expected = [
            $key1,
            $key2,
            $key3,
            $key4,
        ];
        $actual   = $adapter->getKeys();
        sort($actual);
        $this->assertSame($expected, $actual);

        $expected = [
            $key3,
            $key4,
        ];
        $actual   = $adapter->getKeys("one");

        sort($actual);
        $this->assertSame($expected, $actual);
    }

    private function setupTest(AdapterInterface $adapter, string $name): array
    {
        $key1   = uniqid('key');
        $key2   = uniqid('key');
        $key3   = uniqid('one');
        $key4   = uniqid('one');
        $value1 = 'weak' === $name ? new stdClass() : 'test';
        $value2 = 'weak' === $name ? new stdClass() : 'test';
        $value3 = 'weak' === $name ? new stdClass() : 'test';
        $value4 = 'weak' === $name ? new stdClass() : 'test';

        $result = $adapter->set($key1, $value1, 1);
        $this->assertNotFalse($result);
        $result = $adapter->set($key2, $value2, 1);
        $this->assertNotFalse($result);
        $result = $adapter->set($key3, $value3, 1);
        $this->assertNotFalse($result);
        $result = $adapter->set($key4, $value4, 1);
        $this->assertNotFalse($result);

        $actual = $adapter->has($key1);
        $this->assertTrue($actual);
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);
        $actual = $adapter->has($key3);
        $this->assertTrue($actual);
        $actual = $adapter->has($key4);
        $this->assertTrue($actual);

        return [$key1, $key2, $key3, $key4];
    }
}
