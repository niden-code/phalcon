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

use function dirname;

use const DIRECTORY_SEPARATOR;

trait HelperTrait
{
    /**
     * Returns the cache folder
     *
     * @param string $fileName
     *
     * @return string
     */
    protected static function cacheDir(string $fileName = ''): string
    {
        return self::outputDir()
            . 'cache' . DIRECTORY_SEPARATOR
            . $fileName;
    }

    /**
     * Returns the data folder
     *
     * @param string $fileName
     *
     * @return string
     */
    protected static function dataDir(string $fileName = ''): string
    {
        return self::rootDir()
            . 'tests' . DIRECTORY_SEPARATOR
            . 'support' . DIRECTORY_SEPARATOR
            . 'data' . DIRECTORY_SEPARATOR
            . $fileName;
    }

    /**
     * Returns the logs folder
     *
     * @param string $fileName
     *
     * @return string
     */
    protected static function logsDir(string $fileName = ''): string
    {
        return self::outputDir() . 'logs' . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * Returns the output cache folder
     *
     * @param string $fileName
     *
     * @return string
     */
    protected static function outputCacheDir(string $fileName = ''): string
    {
        return self::outputDir()
            . 'cache' . DIRECTORY_SEPARATOR
            . $fileName;
    }

    /**
     * Returns the output cache models folder
     *
     * @param string $fileName
     *
     * @return string
     */
    protected static function outputCacheModelsDir(string $fileName = ''): string
    {
        return self::outputCacheDir()
            . 'models' . DIRECTORY_SEPARATOR
            . $fileName;
    }

    /**
     * Returns the output folder
     *
     * @param string $fileName
     *
     * @return string
     */
    protected static function outputDir(string $fileName = ''): string
    {
        return self::rootDir()
            . 'tests' . DIRECTORY_SEPARATOR
            . 'support' . DIRECTORY_SEPARATOR
            . 'output' . DIRECTORY_SEPARATOR
            . $fileName;
    }

    /**
     * Returns the root folder
     *
     * @param string $fileName
     *
     * @return string
     */
    protected static function rootDir(string $fileName = ''): string
    {
        return dirname(__FILE__, 3) . '/' . $fileName;
    }
}
