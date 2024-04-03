<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests1\Fixtures\Traits;

use Memcached;
use RuntimeException;

trait MemcachedTrait
{
    public Memcached|null $memcached = null;

    /**
     * @var array<string, string|integer>
     */
    protected array $memcachedConfig = [
        'host' => 'localhost',
        'port' => 11211
    ];

    /**
     * Code to run before each test.
     *
     * @return void
     */
    public function memcachedSetup(): void
    {
        if (class_exists('\Memcached')) {
            $this->memcached = new Memcached();
            $options = self::getOptionsLibmemcached();
            $this->memcached->addServer(
                $options['servers'][0]['host'],
                (int) $options['servers'][0]['port']
            );
        } else {
            throw new RuntimeException('Memcache classes not loaded');
        }
    }

    /**
     * Code to run after each test.
     *
     * @return void
     */
    public function memcachedTearDown(): void
    {
        if (empty($this->memcached)) {
            return;
        }

        $this->memcached->flush();
        $this->memcached->quit();
    }

    /**
     * Gets a value from Memcached.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function memcachedGet(string $key): mixed
    {
        return $this->memcached->get($key);
    }

    /**
     * Checks if a key exists in Memcached.
     *
     * @param string     $key
     * @param mixed|null $value
     *
     * @return void
     */
    public function memcachedHas(string $key, mixed $value = null): void
    {
        $actual = $this->memcached->get($key);

        if (null === $value) {
            $this->assertNotFalse($actual, "Cannot find key '{$key}' in Memcached");
        } else {
            $this->assertEquals($value, $actual, "Cannot find key '{$key}' in Memcached with the provided value");
        }
    }

    /**
     * Checks if a key does not exist in Memcached.
     *
     * @param string     $key
     * @param mixed|null $value
     *
     * @return void
     */
    public function memcachedNotHas(string $key, mixed $value = null): void
    {
        $actual = $this->memcached->get($key);

        if (null === $value) {
            $this->assertFalse($actual, "The key '{$key}' exists in Memcached");
        } elseif (false !== $actual) {
            $this->assertEquals($value, $actual, "The key '{$key}' exists in Memcached with the provided value");
        }
    }

    /**
     * Stores an item `$value` with `$key` on the Memcached server.
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $expiration
     *
     * @return void
     */
    public function memcachedSet(string $key, mixed $value, int $expiration = 0): void
    {
        $this->assertTrue($this->memcached->set($key, $value, $expiration));
    }

    /**
     * Flushes all Memcached data.
     *
     * @return void
     */
    public function memcachedClear(): void
    {
        $this->memcached->flush();
    }
}
