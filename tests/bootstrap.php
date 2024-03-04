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

use Dotenv\Dotenv;

error_reporting(E_ALL);
ini_set('max_execution_time', '0');

$rootDir    = dirname(__DIR__);
$autoloader = $rootDir . '/vendor/autoload.php';

if (! file_exists($autoloader)) {
    echo "Composer autoloader not found: $autoloader" . PHP_EOL;
    echo "Please issue 'composer install' and try again." . PHP_EOL;
    exit(1);
}

//require_once 'tests/support/config/functions.php';

$folders = [
    'annotations',
    'assets',
    'cache',
    'cache/models',
    'cache/sessions',
    'coverage',
    'image',
    'image/gd',
    'image/imagick',
    'logs',
    'session',
    'stream',
];

foreach ($folders as $folder) {
    $item = $rootDir . '/tests/support/output/' . $folder;

    if (true !== file_exists($item)) {
        mkdir($item, 0777, true);
    }
}

/**
 * Load .env file
 */
Dotenv::createImmutable($rootDir)->load();

/**
 * These are used for ini tests
 */
if (!defined('PATH_DATA2')) {
    define('PATH_DATA2', $_ENV['PATH_DATA2'] ?? '');
}

if (!defined('PATH_OUTPUT2')) {
    define('PATH_OUTPUT2', $_ENV['PATH_OUTPUT2'] ?? '');
}

require $autoloader;
