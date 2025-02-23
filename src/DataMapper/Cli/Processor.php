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

use Phalcon\DataMapper\Info\Info;
use Phalcon\DataMapper\Pdo\Connection;
use Phalcon\Storage\Filesystem;
use Phalcon\Traits\Helper\Str\InterpolateTrait;
use Throwable;

use function array_unique;
use function file_exists;
use function is_readable;
use function rootDir;
use function trim;
use function ucfirst;

class Processor
{
    use InterpolateTrait;

    public function __construct(
        private readonly Connection $connection,
        private readonly Logger $consoleLogger,
        private readonly Filesystem $fileSystem
    ) {
    }

    public function run(array $config): int
    {
        try {
            $info = Info::new($this->connection);

            $outputDir = $config['outputDir'];
            if (!is_dir($outputDir)) {
                throw new Exception(
                    "Output directory does not exist: [$outputDir]"
                );
            }

            $namespace = trim($config['namespace'], '\\');
            $templates = $this->assertTemplates($config);

            /**
             * Get the tables and loop through each.
             */
            $tables = $info->listTables();
            foreach ($tables as $table) {
                $table = str_replace(
                    '_',
                    '',
                    ucwords(ucfirst($table), '_')
                );
                $this->consoleLogger->info(
                    'i : Processing table ' . $table
                );
                $targetDir = $outputDir . '/' . $table;
                $targetGenerated = $targetDir . '/_generated';
                if (true !== $this->fileSystem->isDirectory($targetDir)) {
                    $this->fileSystem->mkdir($targetDir);
                }
                if (true !== $this->fileSystem->isDirectory($targetGenerated)) {
                    $this->fileSystem->mkdir($targetGenerated);
                }

                foreach ($templates as $template => $file) {
                    $this->consoleLogger->info(
                        'i : Processing template ' . $template
                    );

                    $outputData = $this->fileSystem->get($file);
                    $outputData = $this->toInterpolate(
                        $outputData,
                        [
                            'type'      => $table,
                            'namespace' => $namespace,
                        ]
                    );
                    $outputFile = $targetDir . '/' . str_replace('Type', $table, $template) . '.php';
                    $this->fileSystem->put($outputFile, $outputData);
                }
            }

            return 0;
        } catch (Throwable $ex) {
            $this->consoleLogger->error('X - ' . $ex);
            return 1;
        }
    }

    protected function assertTemplates(array $config): array
    {
        $output    = [];
        $templates = [
//            '_generated/TypeRow_',
//            '_generated/TypeTable_',
            '_generated/TypeTableEvents_',
//            '_generated/TypeTableSelect_',
//            'TypeRow',
//            'TypeTable',
            'TypeTableEvents',
//            'TypeTableSelect',
        ];

        $dirs = [
            rootDir() . '/resources/datamapper/templates',
            rtrim($config['templateDir'], '/'),
        ];
        $dirs = array_unique($dirs);
        foreach ($templates as $template) {
            foreach ($dirs as $dir) {
                $file = str_replace(
                    '/',
                    DIRECTORY_SEPARATOR,
                    "{$dir}/{$template}.tpl.php"
                );

                if (file_exists($file) && is_readable($file)) {
                    $output[$template] = $file;
                }
            }
        }

        return $output;
    }

    public function setTransform() : ?int
    {
        $this->transform = $this->config->transform;

        if (! is_callable($this->transform)) {
            $this->logger->error("Config key 'transform' is not callable.");
            return 1;
        }

        return null;
    }

    protected function getTypes() : ?int
    {
        $tables = $this->info->fetchTableNames();
        foreach ($tables as $table) {

            $type = ($this->transform)($table);

            if ($type === null) {
                continue;
            }

            $this->types[$type] = [
                $table,
                $this->info->fetchColumns($table),
                $this->info->fetchAutoincSequence($table),
            ];
        }

        return null;
    }

    protected function putTypes() : ?int
    {
        $this->logger->info("Generating skeleton data source classes.");
        $this->logger->info("Namespace: " . $this->config->namespace);
        $this->logger->info("Directory: " . $this->directory);

        foreach ($this->types as $type => $info) {
            $this->putFiles($type, ...$info);
        }

        $this->logger->info("Done generating!");
        return null;
    }

    protected function putFiles(string $type, string $table, array $columns, ?string $sequence) : void
    {
        $dir = "{$this->directory}/{$type}";
        $this->mkdir("{$dir}/_generated");

        $vars = [
            'COLDEFS' => $this->getColDefs($columns),
            'COLUMNS' => $columns,
            'DRIVER' => $this->connection->getDriverName(),
            'NAMESPACE' => $this->namespace,
            'RELATED' => $this->getRelated($type),
            'SEQUENCE' => $sequence,
            'TABLE' => $table,
            'TYPE' => $type,
        ];

        foreach ($this->templates as $name => $tpl) {
            $code = $this->render($tpl, $vars);
            $name = str_replace('Type', $type, $name);
            $file = "{$dir}/{$name}.php";
            $this->putFile($file, $code);
        }
    }

    protected function getColDefs(array $columns) : array
    {
        $coldefs = [];

        foreach ($columns as $col) {
            $coldef = $col['type'];
            $unsigned = '';

            if (substr(strtoupper($coldef), -9) == ' UNSIGNED') {
                $unsigned = substr($coldef, -9);
                $coldef = substr($coldef, 0, -9);
            }

            if ($col['size'] !== null) {
                $coldef .= "({$col['size']}";
                if ($col['scale'] !== null) {
                    $coldef .= ",{$col['scale']}";
                }
                $coldef .= ')';
            }

            $coldef .= $unsigned;

            if ($col['notnull'] === true) {
                $coldef .= ' NOT NULL';
            }

            $coldefs[$col['name']] = $coldef;
        }

        return $coldefs;
    }

    protected function getRelated(string $type) : array
    {
        $related = [];
        $class = "{$this->namespace}\\{$type}\\{$type}Related";

        if (! class_exists($class)) {
            return $related;
        }

        $props = (new ReflectionClass($class))->getProperties();

        foreach ($props as $prop) {
            $type = (string) $prop->getType();
            $nullable = substr($type, 0, 1) === '?';

            if ($nullable) {
                $type = '?\\' . substr($type, 1);
            } else {
                $type = '\\' . $type;
            }

            $related[$prop->getName()] = $type;
        }

        return $related;
    }

    protected function mkdir(string $dir) : ?int
    {
        if ($this->fsio->isDir($dir)) {
            $this->logger->info(" Skipped: mkdir {$dir} (already exists)");
            return null;
        }

        try {
            $this->fsio->mkdir($dir, 0755, true);
        } catch (Exception $e) {
            $this->logger->error("-Failure: mkdir {$dir}");
            return 1;
        }

        $this->logger->info("+Success: mkdir {$dir}");
        return null;
    }

    protected function putFile(string $file, string $code) : void
    {
        $overwrite = substr($file, -5) == '_.php';

        if ($this->fsio->isFile($file) && ! $overwrite) {
            $this->logger->info(" Skipped: {$file} (already exists)");
            return;
        }

        $this->fsio->put($file, $code);
        $this->logger->info("+Success: {$file} (generated)");
    }

    protected function render(string $tpl, array $vars) : string
    {
        extract($vars);
        ob_start();
        require $tpl;
        return "<?php" . PHP_EOL . ob_get_clean();
    }
}
