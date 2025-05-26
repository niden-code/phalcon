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

use function outputDir;
use function uniqid;

final class DecrementTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* :: decrement()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterDecrement(
        string $adapterClass,
        array $options,
        string $extension,
        string $name,
        mixed $decrement
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);

        $key = uniqid();

        /**
         * Weak does not implement decrement. It just returns false
         */
        if ('weak' === $name) {
            $actual = $adapter->increment($key);
            $this->assertFalse($actual);
        } else {
            $result = $adapter->set($key, 100);
            $this->assertTrue($result);

            $expected = 99;
            $actual   = $adapter->decrement($key);
            $this->assertSame($expected, $actual);

            $actual = (int)$adapter->get($key);
            $this->assertSame($expected, $actual);

            $expected = 90;
            $actual   = $adapter->decrement($key, 9);
            $this->assertSame($expected, $actual);

            $actual = (int)$adapter->get($key);
            $this->assertSame($expected, $actual);

            $actual = $adapter->delete($key);
            $this->assertTrue($actual);

            /**
             * unknown key
             */
            $key      = uniqid();
            $expected = $decrement;
            $actual   = $adapter->decrement($key);
            $this->assertSame($expected, $actual);

            if ('stream' === $name) {
                $this->safeDeleteDirectory(outputDir('ph-strm-'));
            }
        }
    }
}
