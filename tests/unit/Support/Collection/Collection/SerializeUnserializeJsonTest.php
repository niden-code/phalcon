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

namespace Phalcon\Tests\Unit\Support\Collection\Collection;

use Phalcon\Support\Collection;
use PHPUnit\Framework\TestCase;

use function serialize;

final class SerializeUnserializeJsonTest extends TestCase
{
    /**
     * Tests Phalcon\Support\Collection :: jsonSerialize()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionJsonSerialize(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new Collection($data);

        $expected = $data;
        $actual   = $collection->jsonSerialize();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Collection :: serialize()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionSerialize(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $collection = new Collection($data);

        $expected = serialize($data);
        $actual   = $collection->serialize();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Collection :: unserialize()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCollectionUnserialize(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $serialized = serialize($data);

        $collection = new Collection();

        $collection->unserialize($serialized);

        $expected = $data;
        $actual   = $collection->toArray();
        $this->assertSame($expected, $actual);
    }
}
