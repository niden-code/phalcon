<?php

declare(strict_types=1);

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

ini_set('max_execution_time', '0');

/*******************************************************************************
 * Files
 *******************************************************************************/
/**
 * Returns a new file name
 */
if (!function_exists('getNewFileName2')) {
    /**
     * Returns a unique file name
     *
     * @param string $prefix A prefix for the file
     * @param string $suffix A suffix for the file
     *
     * @return string
     */
    function getNewFileName2(string $prefix = '', string $suffix = 'log'): string
    {
        $prefix = ($prefix) ? $prefix . '_' : '';
        $suffix = ($suffix) ?: 'log';

        return uniqid($prefix, true) . '.' . $suffix;
    }
}

if (!function_exists('safeDeleteFile2')) {
    /**
     * @param string $filename
     */
    function safeDeleteFile2(string $filename): void
    {
        if (file_exists($filename) && is_file($filename)) {
            gc_collect_cycles();
            unlink($filename);
        }
    }
}

/*******************************************************************************
 * Directories
 *******************************************************************************/
/**
 * Returns the root folder
 */
if (!function_exists('rootDir2')) {
    function rootDir2(string $fileName = ''): string
    {
        return dirname(__FILE__, 4) . '/' . $fileName;
    }
}

/**
 * Returns the cache folder
 */
if (!function_exists('cacheDir2')) {
    function cacheDir2(string $fileName = ''): string
    {
        return outputDir2()
            . 'cache' . DIRECTORY_SEPARATOR
            . $fileName;
    }
}

/**
 * Returns the cache models folder
 */
if (!function_exists('cacheModelsDir2')) {
    function cacheModelsDir2(string $fileName = ''): string
    {
        return outputDir2()
            . 'cache' . DIRECTORY_SEPARATOR
            . 'models' . DIRECTORY_SEPARATOR
            . $fileName;
    }
}

/**
 * Returns the data folder
 */
if (!function_exists('dataDir2')) {
    function dataDir2(string $fileName = ''): string
    {
        return rootDir2()
            . 'tests' . DIRECTORY_SEPARATOR
            . 'support' . DIRECTORY_SEPARATOR
            . 'data' . DIRECTORY_SEPARATOR
            . $fileName;
    }
}

/**
 * Returns the logs folder
 */
if (!function_exists('logsDir2')) {
    function logsDir2(string $fileName = ''): string
    {
        return outputDir2() . 'logs' . DIRECTORY_SEPARATOR . $fileName;
    }
}

/**
 * Returns the output folder
 */
if (!function_exists('outputDir2')) {
    function outputDir2(string $fileName = ''): string
    {
        return rootDir2()
            . 'tests' . DIRECTORY_SEPARATOR
            . 'support'  . DIRECTORY_SEPARATOR
            . 'output' . DIRECTORY_SEPARATOR
            . $fileName;
    }
}

/**
 * Safely delete a folder, recursively
 */
if (!function_exists('safeDeleteDirectory2')) {
    /**
     * @param string $directory
     */
    function safeDeleteDirectory2(string $directory): void
    {
        $files = glob($directory . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (str_ends_with($file, '/')) {
                safeDeleteDirectory2($file);
            } else {
                unlink($file);
            }
        }

        if (is_dir($directory)) {
            rmdir($directory);
        }
    }
}

/**
 * Ensures that certain folders are always ready for us.
 */
if (!function_exists('loadFolders2')) {
    function loadFolders2(): void
    {
        $folders = [
            'annotations',
            'assets',
            'cache',
            'cache/models',
            'coverage',
            'image',
            'image/gd',
            'image/imagick',
            'logs',
            'session',
            'stream',
        ];

        foreach ($folders as $folder) {
            $item = outputDir2($folder);

            if (true !== file_exists($item)) {
                mkdir($item, 0777, true);
            }
        }
    }
}

/*******************************************************************************
 * Reflection
 *******************************************************************************/
/**
 * Calls a protected method
 */
if (!function_exists('callProtectedMethod')) {
    /**
     * Calls private or protected method.
     *
     * @param string|object $obj
     * @param string        $method
     *
     * @return mixed
     * @throws ReflectionException
     */
    function callProtectedMethod(string | object $obj, string $method): mixed
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
}

/**
 * Returns the value of a protected property
 */
if (!function_exists('getProtectedProperty2')) {
    /**
     * @param object|string $objectOrString
     * @param string        $propertyName
     *
     * @return mixed
     * @throws ReflectionException
     */
    function getProtectedProperty2(
        object | string $objectOrString,
        string $propertyName
    ): mixed {
        $reflection = new ReflectionClass($objectOrString);

        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($objectOrString);
    }
}

/**
 * @throws ReflectionException
 */
if (!function_exists('setProtectedProperty2')) {
    function setProtectedProperty2($objectOrClass, string $property, mixed $value)
    {
        $reflection = new ReflectionClass($objectOrClass);

        $property = $reflection->getProperty($property);

        $property->setAccessible(true);
        $property->setValue($objectOrClass, $value);
    }
}

/*******************************************************************************
 * Environment
 *******************************************************************************/
/**
 * Converts ENV variables to defined for tests to work
 */
if (!function_exists('loadDefined2')) {
    function loadDefined2(): void
    {
//        defineFromEnv('DATA_MYSQL_CHARSET');
//        defineFromEnv('DATA_MYSQL_HOST');
//        defineFromEnv('DATA_MYSQL_NAME');
//        defineFromEnv('DATA_MYSQL_PASS');
//        defineFromEnv('DATA_MYSQL_PORT');
//        defineFromEnv('DATA_MYSQL_USER');
//
        if (!defined('PATH_DATA2')) {
            define('PATH_DATA2', dataDir2());
        }

        if (!defined('PATH_OUTPUT2')) {
            define('PATH_OUTPUT2', outputDir2());
        }
    }
}

if (!function_exists('env2')) {
    function env2(string $key, $default = null): mixed
    {
        if (defined($key)) {
            return constant($key);
        }

        if (getenv($key) !== false) {
            return getenv($key);
        }

        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('defineFromEnv2')) {
    function defineFromEnv2(string $name): void
    {
        if (defined($name)) {
            return;
        }

        define($name, env2($name));
    }
}

/*******************************************************************************
 * Options
 *******************************************************************************/
if (!function_exists('getOptionsModelCacheStream2')) {
    /**
     * Get Model cache options - Stream
     */
    function getOptionsModelCacheStream2(): array
    {
        if (!is_dir(cacheDir2('models'))) {
            mkdir(
                cacheDir2('models')
            );
        }

        return [
            'lifetime'   => 3600,
            'storageDir' => cacheModelsDir(),
        ];
    }
}

if (!function_exists('getOptionsLibmemcached2')) {
    function getOptionsLibmemcached2(): array
    {
        return [
            'client'  => [],
            'servers' => [
                [
                    'host'   => env2('DATA_MEMCACHED_HOST', '127.0.0.1'),
                    'port'   => env2('DATA_MEMCACHED_PORT', 11211),
                    'weight' => env2('DATA_MEMCACHED_WEIGHT', 0),
                ],
            ],
        ];
    }
}

if (!function_exists('getOptionsMysql2')) {
    /**
     * Get mysql db options
     */
    function getOptionsMysql2(): array
    {
        return [
            'host'     => env2('DATA_MYSQL_HOST'),
            'username' => env2('DATA_MYSQL_USER'),
            'password' => env2('DATA_MYSQL_PASS'),
            'dbname'   => env2('DATA_MYSQL_NAME'),
            'port'     => env2('DATA_MYSQL_PORT'),
            'charset'  => env2('DATA_MYSQL_CHARSET'),
        ];
    }
}

if (!function_exists('getOptionsPostgresql2')) {
    /**
     * Get postgresql db options
     */
    function getOptionsPostgresql2(): array
    {
        return [
            'host'     => env2('DATA_POSTGRES_HOST'),
            'username' => env2('DATA_POSTGRES_USER'),
            'password' => env2('DATA_POSTGRES_PASS'),
            'port'     => env2('DATA_POSTGRES_PORT'),
            'dbname'   => env2('DATA_POSTGRES_NAME'),
            'schema'   => env2('DATA_POSTGRES_SCHEMA'),
        ];
    }
}

if (!function_exists('getOptionsRedis2')) {
    function getOptionsRedis2(): array
    {
        return [
            'host'  => env2('DATA_REDIS_HOST'),
            'port'  => env2('DATA_REDIS_PORT'),
            'index' => env2('DATA_REDIS_NAME'),
        ];
    }
}

if (!function_exists('getOptionsSessionStream2')) {
    /**
     * Get Session Stream options
     */
    function getOptionsSessionStream2(): array
    {
        if (!is_dir(cacheDir2('sessions'))) {
            mkdir(cacheDir2('sessions'));
        }

        return [
            'savePath' => cacheDir2('sessions'),
        ];
    }
}

if (!function_exists('getOptionsSqlite2')) {
    /**
     * Get sqlite db options
     */
    function getOptionsSqlite2(): array
    {
        return [
            'dbname' => rootDir2(env2('DATA_SQLITE_NAME')),
        ];
    }
}

/**
 * Create necessary folders
 */
loadFolders2();
loadDefined2();
