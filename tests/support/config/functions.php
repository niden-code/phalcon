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
 * Directories
 *******************************************************************************/
/**
 * Returns the root folder
 */
if (!function_exists('rootDir')) {
    function rootDir(string $fileName = ''): string
    {
        return dirname(dirname(dirname(dirname(__FILE__)))) . '/' . $fileName;
    }
}

/**
 * Returns the output folder
 */
if (!function_exists('dataDir')) {
    function dataDir(string $fileName = ''): string
    {
        return rootDir() . 'tests/support/data/' . $fileName;
    }
}

/*******************************************************************************
 * Reflection
 *******************************************************************************/
/**
 * Returns the root folder
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
