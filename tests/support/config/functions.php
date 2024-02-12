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
        return dirname(dirname(dirname(dirname(__FILE__)))) . '/' . $fileName;
    }
}

/**
 * Returns the data folder
 */
if (!function_exists('dataDir2')) {
    function dataDir2(string $fileName = ''): string
    {
        return rootDir2() . 'tests/support/data/' . $fileName;
    }
}

/**
 * Returns the logs folder
 */
if (!function_exists('logsDir2')) {
    function logsDir2(string $fileName = ''): string
    {
        return outputDir2() . 'logs/' . $fileName;
    }
}

/**
 * Returns the output folder
 */
if (!function_exists('outputDir2')) {
    function outputDir2(string $fileName = ''): string
    {
        return rootDir2() . 'tests/support/output/' . $fileName;
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
            if (substr($file, -1) == '/') {
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
    function loadFolders2()
    {
        $folders = [
//            'annotations',
//            'assets',
//            'cache',
//            'cache/models',
            'coverage',
//            'image',
//            'image/gd',
//            'image/imagick',
            'logs',
//            'session',
//            'stream',
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
    function callProtectedMethod(string|object $obj, string $method)
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
if (!function_exists('getProtectedProperty')) {
    /**
     * @param object|string $objectOrString
     * @param string        $propertyName
     *
     * @return mixed
     * @throws ReflectionException
     */
    function getProtectedProperty(
        object|string $objectOrString,
        string $propertyName
    ): mixed {
        $reflection = new ReflectionClass($objectOrString);

        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($objectOrString);
    }
}

/**
 * Create necessary folders
 */
loadFolders2();
