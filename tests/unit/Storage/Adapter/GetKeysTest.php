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
use Phalcon\Storage\Adapter\Apcu;
use Phalcon\Storage\Adapter\Libmemcached;
use Phalcon\Storage\Adapter\Memory;
use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Storage\Adapter\ApcuIteratorFixture;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

use function getOptionsLibmemcached2;
use function getOptionsRedis2;
use function phpversion;
use function sort;
use function uniqid;
use function version_compare;

final class GetKeysTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\Apcu :: getKeys()
     *
     * @return void
     *
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('apcu')]
    public function testStorageAdapterApcuGetKeys(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Apcu($serializer);

        $this->assertTrue($adapter->clear());

        $this->runTests($adapter, 'ph-apcu-');
    }

    /**
     * Tests Phalcon\Storage\Adapter\Apcu :: getKeys() - iterator error
     *
     * @return void
     *
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('apcu')]
    public function testStorageAdapterApcuGetKeysIteratorError(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new ApcuIteratorFixture($serializer);

        $this->setupTest($adapter);

        $actual = $adapter->getKeys();
        $this->assertIsArray($actual);
        $this->assertEmpty($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\Libmemcached :: getKeys()
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('memcached')]
    public function testStorageAdapterLibmemcachedGetKeys(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Libmemcached(
            $serializer,
            self::getOptionsLibmemcached()
        );

        $memcachedServerVersions   = $adapter->getAdapter()
                                             ->getVersion()
        ;
        $memcachedExtensionVersion = phpversion('memcached');

        foreach ($memcachedServerVersions as $memcachedServerVersion) {
            // https://www.php.net/manual/en/memcached.getallkeys.php#123793
            // https://bugs.launchpad.net/libmemcached/+bug/1534062
            if (
                version_compare($memcachedServerVersion, '1.4.23', '>=') &&
                version_compare($memcachedExtensionVersion, '3.0.1', '<')
            ) {
                $this->markTestSkipped(
                    'getAllKeys() does not work in certain Memcached versions'
                );
            }

            // https://github.com/php-memcached-dev/php-memcached/issues/367
            if (version_compare($memcachedServerVersion, '1.5.0', '>=')) {
                $this->markTestSkipped(
                    'getAllKeys() does not work in certain Memcached versions'
                );
            }
        }

        $this->assertTrue($adapter->clear());

        $this->runTests($adapter, 'ph-memc-');
    }

    /**
     * Tests Phalcon\Storage\Adapter\Memory :: getKeys()
     *
     * @return void
     *
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterMemoryGetKeys(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Memory($serializer);

        $this->assertTrue($adapter->clear());

        $this->runTests($adapter, 'ph-memo-');
    }

    /**
     * Tests Phalcon\Storage\Adapter\Redis :: getKeys()
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('redis')]
    public function testStorageAdapterRedisGetKeys(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Redis($serializer, self::getOptionsRedis());

        $this->assertTrue($adapter->clear());

        $this->runTests($adapter, 'ph-reds-');
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: getKeys()
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamGetKeys(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => self::outputDir(),
            ]
        );

        $this->assertTrue($adapter->clear());

        $this->runTests($adapter, 'ph-strm');

        $key1 = uniqid('key');
        $key2 = uniqid('key');
        $key3 = uniqid('one');
        $key4 = uniqid('one');

        self::safeDeleteDirectory(self::outputDir('ph-strm'));
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
    public function testStorageAdapterStreamGetKeysIssue14190(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => self::outputDir(),
                'prefix'     => 'basePrefix-',
            ]
        );

        $adapter->clear();

        $actual = $adapter->set('key', 'test');
        $this->assertNotFalse($actual);
        $actual = $adapter->set('key1', 'test');
        $this->assertNotFalse($actual);

        $expected = [
            'basePrefix-key',
            'basePrefix-key1',
        ];

        $actual = $adapter->getKeys();
        sort($actual);

        $this->assertSame($expected, $actual);

        foreach ($expected as $key) {
            $actual = $adapter->delete($key);
            $this->assertTrue($actual);
        }

        self::safeDeleteDirectory(self::outputDir('basePrefix-'));
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
                'storageDir' => self::outputDir(),
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
            'pref-key',
            'pref-key1',
            'pref-somekey',
            'pref-somekey1',
        ];
        $actual   = $adapter->getKeys();
        sort($actual);
        $this->assertSame($expected, $actual);

        $expected = [
            'pref-somekey',
            'pref-somekey1',
        ];

        $actual = $adapter->getKeys('so');
        sort($actual);
        $this->assertSame($expected, $actual);

        $actual = $adapter->clear();
        $this->assertTrue($actual);

        self::safeDeleteDirectory(self::outputDir('pref-'));
    }

    /**
     * @param AdapterInterface $adapter
     * @param string           $prefix
     *
     * @return void
     */
    private function runTests(
        AdapterInterface $adapter,
        string $prefix
    ): void {
        [$key1, $key2, $key3, $key4] = $this->setupTest($adapter);

        $expected = [
            $prefix . $key1,
            $prefix . $key2,
            $prefix . $key3,
            $prefix . $key4,
        ];
        $actual   = $adapter->getKeys();
        sort($actual);
        $this->assertSame($expected, $actual);

        $expected = [
            $prefix . $key3,
            $prefix . $key4,
        ];
        $actual   = $adapter->getKeys("one");
        sort($actual);
        $this->assertSame($expected, $actual);
    }

    private function setupTest(AdapterInterface $adapter): array
    {
        $key1 = uniqid('key');
        $key2 = uniqid('key');
        $key3 = uniqid('one');
        $key4 = uniqid('one');

        $result = $adapter->set($key1, 'test');
        $this->assertNotFalse($result);
        $result = $adapter->set($key2, 'test');
        $this->assertNotFalse($result);
        $result = $adapter->set($key3, 'test');
        $this->assertNotFalse($result);
        $result = $adapter->set($key4, 'test');
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
