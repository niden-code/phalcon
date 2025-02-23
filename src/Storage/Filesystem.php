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

namespace Phalcon\Storage;

use function error_get_last;
use function error_reporting;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function filesize;
use function glob;
use function hash_file;
use function is_dir;
use function is_file;
use function is_readable;
use function is_writable;
use function mkdir;
use function unlink;

use const LOCK_EX;

/**
 * File operations using plain files
 */
class Filesystem
{
    /**
     * Delete a file
     *
     * @param string $file
     *
     * @return bool
     * @throws Exception
     */
    public function delete(string $file): bool
    {
        $level  = error_reporting(0);
        $result = unlink($file);
        error_reporting($level);

        if (false !== $result) {
            return true;
        }

        $error = error_get_last();
        throw new Exception($error['message'] ?? '');
    }

    /**
     * Get the contents of a file.
     *
     * @param string $file
     *
     * @return string
     * @throws Exception
     */
    public function get(string $file): string
    {
        $level  = error_reporting(0);
        $result = file_get_contents($file);
        error_reporting($level);

        if (false !== $result) {
            return $result;
        }

        $error = error_get_last();
        throw new Exception($error['message'] ?? '');
    }

    /**
     * Find path names matching a given pattern.
     *
     * @param string $pattern
     * @param int    $flags
     *
     * @return array|false
     */
    public function glob(string $pattern, int $flags = 0): array | false
    {
        return glob($pattern, $flags);
    }

    /**
     * Determine if a file or directory exists.
     *
     * @param string $file
     *
     * @return bool
     */
    public function has(string $file): bool
    {
        return file_exists($file);
    }

    /**
     * Get the hash of the file
     *
     * @param string $file
     * @param string $algorithm
     *
     * @return string|false
     */
    public function hash(string $file, string $algorithm = 'sha1'): string | false
    {
        return hash_file($algorithm, $file);
    }

    /**
     * Return if the passed path is a directory
     *
     * @param string $path
     *
     * @return bool
     */
    public function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * Return if the file exists, is a file and is readable
     *
     * @param string $file
     *
     * @return bool
     */
    public function isFile(string $file): bool
    {
        return file_exists($file) && is_file($file) && is_readable($file);
    }

    /**
     * Determine if the given path is readable.
     *
     * @param string $file
     *
     * @return bool
     */
    public function isReadable(string $file): bool
    {
        return is_readable($file);
    }

    /**
     * Determine if the given path is writable.
     *
     * @param string $file
     *
     * @return bool
     */
    public function isWritable(string $file): bool
    {
        return is_writable($file);
    }

    /**
     * Create a directory
     *
     * @param string $directory
     * @param int    $mode
     * @param bool   $recursive
     *
     * @return void
     * @throws Exception
     */
    public function mkdir(
        string $directory,
        int $mode = 0777,
        bool $recursive = true
    ): void {
        $level  = error_reporting(0);
        $result = mkdir($directory, $mode, $recursive);
        error_reporting($level);

        if (false === $result) {
            $error = error_get_last();
            throw new Exception($error['message'] ?? '');
        }
    }

    /**
     * Store the contents in a file with or without a lock
     *
     * @param string $file
     * @param string $contents
     * @param bool   $lock
     *
     * @return int
     * @throws Exception
     */
    public function put(string $file, string $contents, bool $lock = false): int
    {
        $level  = error_reporting(0);
        $result = file_put_contents($file, $contents, $lock ? LOCK_EX : 0);
        error_reporting($level);

        if (false !== $result) {
            return $result;
        }

        $error = error_get_last();
        throw new Exception($error['message'] ?? '');
    }

    /**
     * Return the size of the file, false otherwise
     *
     * @param string $file
     *
     * @return false|int
     */
    public function size(string $file): false | int
    {
        return filesize($file);
    }
}
