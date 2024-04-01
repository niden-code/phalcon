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

namespace Phalcon\Tests\Unit\Cache\Adapter\Libmemcached;

use Codeception\Example;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Cache\Adapter\Libmemcached;
use Phalcon\Cache\Exception as CacheException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use stdClass;

use function getOptionsLibmemcached;

#[RequiresPhpExtension('memcached')]
final class GetSetTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cache\Adapter\Libmemcached :: get()/set()
     *
     * @dataProvider providerExamples
     *
     * @return void
     * @param Example           $example
     *
     * @throws CacheException
     * @throws HelperException
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testCacheAdapterLibmemcachedGetSet(
        mixed $value
    ): void {
        $serializer = new SerializerFactory();
        $adapter    = new Libmemcached(
            $serializer,
            self::getOptionsLibmemcached()
        );

        $key    = uniqid();
        $actual = $adapter->set($key, $value);
        $this->assertTrue($actual);

        $expected = $value;
        $actual   = $adapter->get($key);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Phalcon\Cache\Adapter\Libmemcached :: get()/set() - custom
     * serializer
     *
     * @return void
     *
     * @throws CacheException\Exception
     * @throws HelperException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterLibmemcachedGetSetCustomSerializer(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Libmemcached(
            $serializer,
            array_merge(
                self::getOptionsLibmemcached(),
                [
                    'defaultSerializer' => 'Base64',
                ]
            )
        );

        $key    = uniqid();
        $source = 'Phalcon Framework';
        $actual = $adapter->set($key, $source);
        $this->assertTrue($actual);

        $expected = $source;
        $actual   = $adapter->get($key);
        $this->assertSame($expected, $actual);
    }

    public static function providerExamples(): array
    {
        return [
            [
                'random string',
            ],
            [
                123456,
            ],
            [
                123.456,
            ],
            [
                true,
            ],
            [
                false,
            ],
            [
                null,
            ],
            [
                new stdClass(),
            ],
        ];
    }
}
