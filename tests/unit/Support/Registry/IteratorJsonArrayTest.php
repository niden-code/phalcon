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

use function json_encode;

use const JSON_PRETTY_PRINT;

final class IteratorJsonArrayTest extends TestCase
{
    /**
     * Tests Phalcon\Support\Registry :: getIterator()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistryGetIterator(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $registry = new Registry($data);

        foreach ($registry as $key => $value) {
            $expected = $data[$key];
            $actual   = $registry[$key];
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Support\Registry :: toArray()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistryToArray(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $registry = new Registry($data);

        $expected = $data;
        $actual   = $registry->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Support\Registry :: toJson()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testRegistryToJson(): void
    {
        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $registry = new Registry($data);

        $expected = json_encode($data);
        $actual   = $registry->toJson();
        $this->assertSame($expected, $actual);

        $expected = json_encode($data, JSON_PRETTY_PRINT);
        $actual   = $registry->toJson(JSON_PRETTY_PRINT);
        $this->assertSame($expected, $actual);
    }
}
