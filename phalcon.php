<?php

use Phalcon\DataMapper\Cli\Console;

error_reporting(E_ALL);

$autoloader = __DIR__ . '/vendor/autoload.php';

if (! file_exists($autoloader)) {
    echo "Composer autoloader not found: $autoloader" . PHP_EOL;
    echo "Please issue 'composer install' and try again." . PHP_EOL;
    exit(1);
}

require_once $autoloader;

if (file_exists('.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

/**
 * Returns the top level folder
 *
 * @return string
 */
function rootDir(): string
{
    return __DIR__;
}


$console = new Console();
$code = $console->run();
