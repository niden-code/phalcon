<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Implementation of this file has been influenced by AtlasPHP
 *
 * @link    https://github.com/atlasphp/Atlas.Cli
 * @license https://github.com/atlasphp/Atlas.Cli/blob/2.x/LICENSE.md
 */

declare(strict_types=1);

namespace Phalcon\DataMapper\Cli;

use PDO;
use Phalcon\DataMapper\Pdo\Connection;
use Phalcon\Storage\Adapter\Stream;
use Phalcon\Storage\Exception as StorageException;
use Phalcon\Storage\Filesystem;
use Phalcon\Storage\SerializerFactory;
use Throwable;

use function file_exists;
use function getopt;
use function is_array;
use function is_readable;

/**
 * @phpstan-type TDsn = array{
 *     dsn: string,
 *     username: string|null,
 *     password: string|null,
 * }
 *
 */
class Console
{
    public function run(): int
    {
        /**
         * Create the console logger
         */
        $consoleLogger = new Logger();

        try {
            /**
             * Check the configuration
             */
            $config     = $this->assertConfig();
            $connection = $this->getConnection($config['pdo']);
            $fileSystem = new Filesystem();
            $processor  = new Processor(
                $connection,
                $consoleLogger,
                $fileSystem
            );
            $code      = $processor->run($config);
        } catch (Throwable $ex) {
            $consoleLogger->error('X - ' . $ex->getMessage());
            $code = 1;
        }

        return $code;
    }

    /**
     * @return array
     */
    private function assertConfig(): array
    {
        $arguments = getopt('', ['configuration:']);

        $configFile = $arguments['configuration'] ?? '';
        if ('' === $configFile) {
            throw new Exception(
                'No configuration file provided'
            );
        }

        if (true !== file_exists($configFile)) {
            throw new Exception(
                "Configuration file not found [$configFile]"
            );
        }

        if (true !== is_readable($configFile)) {
            throw new Exception(
                "Configuration file is not readable [$configFile]"
            );
        }

        $config = require_once $configFile;

        if (true !== is_array($config)) {
            throw new Exception('Configuration file must return an array');
        }

        $dsn = $config['pdo']['dsn'] ?? '';
        if (true === empty($dsn)) {
            throw new Exception(
                'The configuration file must have a DSN defined'
            );
        }

        return $config;
    }

    /**
     * @param TDsn $pdo
     *
     * @return Connection
     */
    private function getConnection(array $pdo): Connection
    {
        $connection = Connection::new(
            $pdo['dsn'],
            $pdo['username'],
            $pdo['password'],
        );

        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

        return $connection;
    }
}
