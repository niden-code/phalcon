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

namespace Phalcon\Tests\Unit\Cache\Adapter\Stream;

use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Cache\Exception as CacheException;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Support\Exception as HelperException;
use stdClass;

use function file_put_contents;
use function is_dir;
use function mkdir;
use function outputDir;
use function sleep;

final class GetSetTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cache\Adapter\Stream :: set()
     *
     * @return void
     *
     * @throws HelperException
     * @throws CacheException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterStreamSet(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => self::outputDir(),
            ]
        );

        $data   = 'Phalcon Framework';
        $actual = $adapter->set('test-key', $data);
        $this->assertTrue($actual);

        $target = self::outputDir() . 'ph-strm/te/st/-k/';
        $file   = $target . 'test-key';
        $contents = file_get_contents($file);
        $expected = 's:3:"ttl";i:3600;s:7:"content";s:25:"s:17:"Phalcon Framework";";}';
        $this->assertStringContainsString($expected, $contents);

        self::safeDeleteFile($target . 'test-key');
    }

    /**
     * Tests Phalcon\Cache\Adapter\Stream :: get()
     *
     * @return void
     *
     * @throws HelperException
     * @throws CacheException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterStreamGet(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => self::outputDir(),
            ]
        );

        $target = self::outputDir() . 'ph-strm/te/st/-k/';
        $data   = 'Phalcon Framework';
        $actual = $adapter->set('test-key', $data);
        $this->assertTrue($actual);

        $expected = 'Phalcon Framework';
        $actual   = $adapter->get('test-key');
        $this->assertNotNull($actual);
        $this->assertSame($expected, $actual);

        $expected        = new stdClass();
        $expected->one   = 'two';
        $expected->three = 'four';

        $actual = $adapter->set('test-key', $expected);
        $this->assertTrue($actual);

        $actual = $adapter->get('test-key');
        $this->assertEquals($expected, $actual);

        self::safeDeleteFile($target . 'test-key');
    }

    /**
     * Tests Phalcon\Cache\Adapter\Stream :: get() - errors
     *
     * @return void
     *
     * @throws HelperException
     * @throws CacheException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testCacheAdapterStreamGetErrors(): void
    {
        $serializer = new SerializerFactory();
        $adapter    = new Stream(
            $serializer,
            [
                'storageDir' => self::outputDir(),
            ]
        );

        $target = self::outputDir() . 'ph-strm/te/st/-k/';
        if (true !== is_dir($target)) {
            mkdir($target, 0777, true);
        }

        // Unknown key
        $expected = 'test';
        $actual   = $adapter->get(uniqid(), 'test');
        $this->assertSame($expected, $actual);

        // Invalid stored object
        $actual = file_put_contents(
            $target . 'test-key',
            '{'
        );
        $this->assertNotFalse($actual);

        $expected = 'test';
        $actual   = $adapter->get('test-key', 'test');
        $this->assertSame($expected, $actual);

        // Expiry
        $data = 'Phalcon Framework';

        $actual = $adapter->set('test-key', $data, 1);
        $this->assertTrue($actual);

        sleep(2);

        $expected = 'test';
        $actual   = $adapter->get('test-key', 'test');
        $this->assertSame($expected, $actual);

        self::safeDeleteFile($target . 'test-key');
    }
}
