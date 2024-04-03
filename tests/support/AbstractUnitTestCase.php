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

namespace Phalcon\Tests\Support;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

use function array_slice;
use function array_unshift;
use function call_user_func_array;
use function dirname;
use function file_exists;
use function func_get_args;
use function function_exists;
use function gc_collect_cycles;
use function glob;
use function is_dir;
use function is_file;
use function is_object;
use function rmdir;
use function uniqid;
use function unlink;

use const DIRECTORY_SEPARATOR;
use const GLOB_MARK;

abstract class AbstractUnitTestCase extends TestCase
{
    use HelperTrait;

    /**
     * Calls private or protected method.
     *
     * @param string|object $obj
     * @param string        $method
     *
     * @return mixed
     * @throws ReflectionException
     */
    protected static function callProtectedMethod(string | object $obj, string $method): mixed
    {
        $reflectionClass = new ReflectionClass($obj);

        $reflectionMethod = $reflectionClass->getMethod($method);

        $reflectionMethod->setAccessible(true);

        if (!is_object($obj)) {
            $obj = $reflectionClass->newInstanceWithoutConstructor();
        }

        // $obj, $method
        $args = array_slice(func_get_args(), 2);

        array_unshift($args, $obj);

        return call_user_func_array(
            [$reflectionMethod, 'invoke'],
            $args
        );
    }

    /**
     * Gets an environment value
     *
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    protected static function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Returns a unique file name
     *
     * @param string $prefix A prefix for the file
     * @param string $suffix A suffix for the file
     *
     * @return string
     */
    protected static function getNewFileName(string $prefix = '', string $suffix = 'log'): string
    {
        $prefix = ($prefix) ? $prefix . '_' : '';
        $suffix = ($suffix) ?: 'log';

        return uniqid($prefix, true) . '.' . $suffix;
    }

    /**
     * Return the Libmemcached options
     *
     * @return array
     */
    protected static function getOptionsLibmemcached(): array
    {
        return [
            'client'  => [],
            'servers' => [
                [
                    'host'   => self::env('DATA_MEMCACHED_HOST', '127.0.0.1'),
                    'port'   => self::env('DATA_MEMCACHED_PORT', 11211),
                    'weight' => self::env('DATA_MEMCACHED_WEIGHT', 0),
                ],
            ],
        ];
    }

    /**
     * Get Model cache options - Stream
     *
     * @return array
     */
    protected static function getOptionsModelCacheStream(): array
    {
        return [
            'lifetime'   => 3600,
            'storageDir' => self::outputCacheModelsDir(),
        ];
    }

    /**
     * Return the Mysql options
     *
     * @return array
     */
    protected static function getOptionsMysql(): array
    {
        return [
            'host'     => self::env('DATA_MYSQL_HOST'),
            'username' => self::env('DATA_MYSQL_USER'),
            'password' => self::env('DATA_MYSQL_PASS'),
            'dbname'   => self::env('DATA_MYSQL_NAME'),
            'port'     => self::env('DATA_MYSQL_PORT'),
            'charset'  => self::env('DATA_MYSQL_CHARSET'),
        ];
    }

    /**
     * Return the Postgres options
     *
     * @return array
     */
    protected static function getOptionsPostgresql(): array
    {
        return [
            'host'     => self::env('DATA_POSTGRES_HOST'),
            'username' => self::env('DATA_POSTGRES_USER'),
            'password' => self::env('DATA_POSTGRES_PASS'),
            'port'     => self::env('DATA_POSTGRES_PORT'),
            'dbname'   => self::env('DATA_POSTGRES_NAME'),
            'schema'   => self::env('DATA_POSTGRES_SCHEMA'),
        ];
    }

    /**
     * Return the Redis options
     *
     * @return array
     */
    protected static function getOptionsRedis(): array
    {
        return [
            'host'  => self::env('DATA_REDIS_HOST'),
            'port'  => self::env('DATA_REDIS_PORT'),
            'index' => self::env('DATA_REDIS_NAME'),
        ];
    }

    /**
     * Return the Session stream options
     *
     * @return array
     */
    protected static function getOptionsSessionStream(): array
    {
        return [
            'savePath' => self::outputCacheDir('sessions'),
        ];
    }

    /**
     * Return the Sqlite options
     *
     * @return array
     */
    protected static function getOptionsSqlite(): array
    {
        return [
            'dbname' => self::rootDir(self::env('DATA_SQLITE_NAME')),
        ];
    }

    /**
     * Returns the value of a protected property
     *
     * @param mixed  $objectOrString
     * @param string $propertyName
     *
     * @return mixed
     * @throws ReflectionException
     */
    protected static function getProtectedProperty(
        mixed $objectOrString,
        string $propertyName
    ): mixed {
        $reflection = new ReflectionClass($objectOrString);

        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($objectOrString);
    }

    /**
     * Safely delete a folder, recursively
     *
     * @param string $directory
     *
     * @return void
     */
    protected static function safeDeleteDirectory(string $directory): void
    {
        $files = glob($directory . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (str_ends_with($file, '/')) {
                self::safeDeleteDirectory($file);
            } else {
                unlink($file);
            }
        }

        if (is_dir($directory)) {
            rmdir($directory);
        }
    }

    /**
     * Safely deletes a file if one is there
     *
     * @param string $filename
     */
    protected static function safeDeleteFile(string $filename): void
    {
        if (file_exists($filename) && is_file($filename)) {
            gc_collect_cycles();
            unlink($filename);
        }
    }

    /**
     * Sets a protected or private property
     *
     * @param mixed  $objectOrClass
     * @param string $property
     * @param mixed  $value
     *
     * @return void
     * @throws ReflectionException
     */
    protected static function setProtectedProperty(
        mixed $objectOrClass,
        string $property,
        mixed $value
    ): void {
        $reflection = new ReflectionClass($objectOrClass);

        $property = $reflection->getProperty($property);

        $property->setAccessible(true);
        $property->setValue($objectOrClass, $value);
    }
}
