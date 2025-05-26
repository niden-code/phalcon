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

final class GetAdapterTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* :: getAdapter()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetAdapter(
        string $adapterClass,
        array $options,
        string $extension,
        string $name,
        mixed $decrement,
        mixed $increment,
        ?string $adapterInstance,
    ): void {
        if (!empty($extension)) {
            $this->checkExtensionIsLoaded($extension);
        }

        $serializer = new SerializerFactory();
        $adapter    = new $adapterClass($serializer, $options);

        $actual = $adapter->getAdapter();

        if (null === $adapterInstance) {
            $this->assertNull($actual);
        } else {
            $this->assertInstanceOf($adapterInstance, $actual);
        }
    }
}
