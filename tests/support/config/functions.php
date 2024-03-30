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

ini_set('max_execution_time', '0');

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
 * Environment
 *******************************************************************************/
/**
 * Converts ENV variables to defined for tests to work
 */
if (!function_exists('loadDefined2')) {
    function loadDefined2(): void
    {
        if (!defined('PATH_DATA2')) {
            define('PATH_DATA2', dataDir2());
        }

        if (!defined('PATH_OUTPUT2')) {
            define('PATH_OUTPUT2', outputDir2());
        }
    }
}

/**
 * Create necessary folders
 */
loadFolders2();
loadDefined2();
