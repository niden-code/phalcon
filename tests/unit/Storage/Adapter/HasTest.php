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

use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use Phalcon\Tests\Fixtures\Storage\Adapter\StreamFileGetContentsFixture;
use Phalcon\Tests\Fixtures\Storage\Adapter\StreamFopenFixture;
use Phalcon\Tests\Unit\Storage\AbstractStorageTestCase;
use stdClass;

use function outputDir;
use function uniqid;

final class HasTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* :: has()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterHas(
        string $abstractClass,
        array $options,
        string $extension,
        string $name
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $abstractClass($serializer, $options);

        $key   = uniqid();
        $value = 'test';

        if ('weak' === $name) {
            $value = new stdClass();
        }

        $actual = $adapter->has($key);
        $this->assertFalse($actual);

        $adapter->set($key, $value);
        $actual = $adapter->has($key);
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: has() - cannot open file
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamHasCannotOpenFile(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new StreamFopenFixture(
            $serializer,
            [
                'storageDir' => outputDir(),
            ],
        );

        $key    = uniqid();
        $actual = $adapter->set($key, 'test');
        $this->assertTrue($actual);

        $actual = $adapter->has($key);
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: has() - empty payload
     *
     * @return void
     *
     * @throws HelperException
     * @throws StorageException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamHasEmptyPayload(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new StreamFileGetContentsFixture(
            $serializer,
            [
                'storageDir' => outputDir(),
            ],
        );

        $key    = uniqid();
        $actual = $adapter->set($key, 'test');
        $this->assertTrue($actual);

        $actual = $adapter->has($key);
        $this->assertFalse($actual);
    }
}
