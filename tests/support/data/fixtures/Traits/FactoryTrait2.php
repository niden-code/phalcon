<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests1\Fixtures\Traits;

use Phalcon\Config\Adapter\Ini;
use Phalcon\Config\Config;
use Phalcon\Config\ConfigInterface;

/**
 * Trait FactoryTrait
 *
 * @property ConfigInterface $config
 * @property array           $arrayConfig
 */
trait FactoryTrait2
{
    /**
     * @var array
     */
    protected array $arrayConfig;
    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * Initializes the main config
     *
     * @return void
     */
    protected function init(): void
    {
        $configFile = self::dataDir('assets/config/factory.ini');

        $this->config = new Ini($configFile, INI_SCANNER_NORMAL);

        $this->arrayConfig = $this->config->toArray();
    }

    /**
     * Initializes the logger config - this is special because it is nested
     *
     * @return void
     */
    protected function initLogger(): void
    {
        $options = [
            'logger' => [
                'name'     => 'my-logger',
                'adapters' => [
                    0 => [
                        'adapter' => 'stream',
                        'name'    => self::outputDir('tests/logs/factory.log'),

                    ],
                    1 => [
                        'adapter' => 'stream',
                        'name'    => self::outputDir('tests/logs/factory.log'),

                    ],
                ],
            ],
        ];

        $this->config = new Config($options);

        $this->arrayConfig = $this->config->toArray();
    }
}
