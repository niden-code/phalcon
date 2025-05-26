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

final class GetSetDefaultSerializerTest extends AbstractStorageTestCase
{
    /**
     * Tests Phalcon\Storage\Adapter\* ::
     * getDefaultSerializer()/setDefaultSerializer()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testStorageAdapterGetSetDefaultSerializer(
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

        $expected = 'weak' === $name ? 'none' : 'php';
        $actual   = $adapter->getDefaultSerializer();
        $this->assertSame($expected, $actual);

        $adapter->setDefaultSerializer('Base64');

        $expected = 'weak' === $name ? 'none' : 'base64';
        $actual   = $adapter->getDefaultSerializer();
        $this->assertSame($expected, $actual);
    }
}
