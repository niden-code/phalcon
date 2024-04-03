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

use Phalcon\Storage\Adapter\Redis;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

use function array_merge;
use function file_put_contents;
use function getOptionsRedis2;
use function is_dir;
use function mkdir;
use function sleep;
use function uniqid;

final class ExceptionsTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\Redis :: get() - failed auth
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('memcached')]
    public function testStorageAdapterRedisGetSetFailedAuth(): void
    {
        $this->expectException(StorageException::class);
        $this->expectExceptionMessage(
            'Failed to authenticate with the Redis server'
        );

        $serializer = new SerializerFactory();
        $adapter    = new Redis(
            $serializer,
            array_merge(
                self::getOptionsRedis(),
                [
                    'auth' => 'something',
                ]
            )
        );

        $adapter->get('test');
    }

    /**
     * Tests Phalcon\Storage\Adapter\Redis :: get() - wrong index
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[RequiresPhpExtension('redis')]
    public function testStorageAdapterRedisGetSetWrongIndex(): void
    {
        $this->expectException(StorageException::class);
        $this->expectExceptionMessage('Redis server selected database failed');

        $serializer = new SerializerFactory();
        $adapter    = new Redis(
            $serializer,
            array_merge(
                self::getOptionsRedis(),
                [
                    'index' => 99,
                ]
            )
        );

        $adapter->get('test');
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: get() - errors
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamGetErrors(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => self::outputDir(),
            ]
        );

        $target = self::outputDir() . 'ph-strm/te/st/-k/';
        if (true !== is_dir($target)) {
            mkdir($target, 0777, true);
        }

        // Unknown key
        $expected = 'test';
        $actual   = $adapter->get(uniqid(), 'test');
        $this->assertSame($expected, $actual);

        // Invalid stored object
        $actual = file_put_contents(
            $target . 'test-key',
            '{'
        );
        $this->assertNotFalse($actual);

        $expected = 'test';
        $actual   = $adapter->get('test-key', 'test');
        $this->assertSame($expected, $actual);

        // Expiry
        $data = 'Phalcon Framework';

        $actual = $adapter->set('test-key', $data, 1);
        $this->assertTrue($actual);

        sleep(2);

        $expected = 'test';
        $actual   = $adapter->get('test-key', 'test');
        $this->assertSame($expected, $actual);

        $this->safeDeleteFile($target . 'test-key');
    }
}
