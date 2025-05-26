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

use Phalcon\Storage\SerializerFactory;
use Phalcon\Tests\Unit\Storage\AbstractStorageTestCase;
use stdClass;

use function uniqid;

final class DeleteTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* :: delete()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterDelete(
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
        $value = 'test';

        if ('weak' === $name) {
            $value     = new stdClass();
            $value->id = 1;
        }

        $adapter->set($key, $value);
        $actual = $adapter->has($key);
        $this->assertTrue($actual);

        $actual = $adapter->delete($key);
        $this->assertTrue($actual);

        $actual = $adapter->has($key);
        $this->assertFalse($actual);

        /**
         * Call clear twice to ensure it returns false
         */
        $actual = $adapter->delete($key);
        $this->assertFalse($actual);

        /**
         * Delete unknown
         */
        $key    = uniqid();
        $actual = $adapter->delete($key);
        $this->assertFalse($actual);
    }
}
