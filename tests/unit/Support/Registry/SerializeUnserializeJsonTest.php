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

namespace Phalcon\Tests\Unit\Support\Registry;

use Phalcon\Support\Registry;
use PHPUnit\Framework\TestCase;

use function serialize;

final class SerializeUnserializeJsonTest extends TestCase
{
    /**
     * Tests Phalcon\Support\Registry :: jsonSerialize()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistryJsonSerialize(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $registry = new Registry($data);

        $expected = $data;
        $actual   = $registry->jsonSerialize();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Registry :: serialize()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistrySerialize(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $registry = new Registry($data);

        $expected = serialize($data);
        $actual   = $registry->serialize();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Registry :: unserialize()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistryUnserialize(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $serialized = serialize($data);

        $registry = new Registry();

        $registry->unserialize($serialized);

        $expected = $data;
        $actual   = $registry->toArray();
        $this->assertSame($expected, $actual);
    }
}
