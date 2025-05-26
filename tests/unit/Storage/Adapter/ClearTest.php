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

use Exception;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Fixtures\Storage\Adapter\ApcuApcuDeleteFixture;
use Phalcon\Tests\Fixtures\Storage\Adapter\StreamUnlinkFixture;
use Phalcon\Tests\Unit\Storage\AbstractStorageTestCase;
use stdClass;

use function uniqid;

final class ClearTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\Apcu :: clear() - delete error
     *
     * @return void
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterApcuClearDeleteError(): void
    {
        $this->checkExtensionIsLoaded('apcu');

        $serializer = new SerializerFactory();
        $adapter    = new ApcuApcuDeleteFixture($serializer);

        $key1 = uniqid();
        $key2 = uniqid();
        $adapter->set($key1, 'test');
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, 'test');
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\Apcu :: clear() - iterator error
     *
     * @return void
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterApcuClearIteratorError(): void
    {
        $this->checkExtensionIsLoaded('apcu');

        $serializer = new SerializerFactory();
        $adapter    = new ApcuApcuDeleteFixture($serializer);

        $key1 = uniqid();
        $key2 = uniqid();
        $adapter->set($key1, 'test');
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, 'test');
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\* :: clear()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterClear(
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

        $key1   = uniqid();
        $key2   = uniqid();
        $value1 = 'test';
        $value2 = 'test';

        if ('weak' === $name) {
            $value1     = new stdClass();
            $value1->id = 1;
            $value2     = new stdClass();
            $value2->id = 2;
        }

        $adapter->set($key1, $value1);
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, $value2);
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertTrue($actual);

        $actual = $adapter->has($key1);
        $this->assertFalse($actual);

        $actual = $adapter->has($key2);
        $this->assertFalse($actual);

        /**
         * Call clear twice to ensure it returns true
         */
        $actual = $adapter->clear();
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Storage\Adapter\Stream :: clear() - cannot delete file
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testStorageAdapterStreamClearCannotDeleteFile(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new StreamUnlinkFixture(
            $serializer,
            [
                'storageDir' => outputDir(),
            ],
        );

        $key1 = uniqid();
        $key2 = uniqid();
        $adapter->set($key1, 'test');
        $actual = $adapter->has($key1);
        $this->assertTrue($actual);

        $adapter->set($key2, 'test');
        $actual = $adapter->has($key2);
        $this->assertTrue($actual);

        $actual = $adapter->clear();
        $this->assertFalse($actual);

        $this->safeDeleteDirectory(outputDir('ph-strm-'));
    }
}
