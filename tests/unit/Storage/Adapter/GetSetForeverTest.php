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

final class GetSetForeverTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* :: get()/setForever()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetSetForever(
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

        $key   = uniqid();
        $value = 'test';

        if ('weak' === $name) {
            $value     = new stdClass();
            $value->id = 1;
        }

        $result = $adapter->setForever($key, $value);
        $this->assertTrue($result);

        sleep(2);
        $result = $adapter->has($key);
        $this->assertTrue($result);

        /**
         * Delete it
         */
        $result = $adapter->delete($key);
        $this->assertTrue($result);
    }
}
