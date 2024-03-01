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

namespace Phalcon\Tests\Unit\Config\ConfigFactory;

use Codeception\Example;
use Phalcon\Config\Adapter\Grouped;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Config\Adapter\Json;
use Phalcon\Config\Adapter\Php;
use Phalcon\Config\Adapter\Yaml;
use Phalcon\Config\ConfigFactory;
use Phalcon\Config\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use UnitTester;

use function dataDir;
use function dataDir2;

final class NewInstanceTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger\LoggerFactory :: newInstance()
     *
     * @dataProvider getExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2021-10-18
     */
    public function testConfigFactoryNewInstance(
        string $service,
        array|string $options,
        string $expected
    ): void {
        $factory = new ConfigFactory();
        $config  = $factory->newInstance($service, $options);

        $this->assertInstanceOf($expected, $config);
    }

    /**
     * Tests Phalcon\Logger\LoggerFactory :: newInstance() - grouped
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2021-10-18
     */
    public function testConfigFactoryNewInstanceGrouped(): void
    {
        $factory = new ConfigFactory();
        $options = [
            [
                'filePath' => self::dataDir('assets/config/config.json'),
            ],
            [
                'adapter' => 'array',
                'config'  => [
                    'test' => [
                        'property2' => 'something-else',
                    ],
                ],
            ],
        ];

        $config  = $factory->newInstance('grouped', $options, 'json');
        $expected = Grouped::class;
        $this->assertInstanceOf($expected, $config);
    }

    /**
     * Tests Phalcon\Config\ConfigFactory :: newInstance() - exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-18
     */
    public function testConfigFactoryNewInstanceException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Service unknown is not registered");

        $factory = new ConfigFactory();
        $adapter = $factory->newInstance("unknown", "config.php");
    }

    /**
     * @return array<array-key, array<string, string>>
     */
    public static function getExamples(): array
    {
        return [
            [
                'ini',
                self::dataDir('assets/config/config.ini'),
                Ini::class,
            ],
            [
                'json',
                self::dataDir('assets/config/config.json'),
                Json::class,
            ],
            [
                'php',
                self::dataDir('assets/config/config.php'),
                Php::class,
            ],
            [
                'yaml',
                self::dataDir('assets/config/config.yml'),
                Yaml::class,
            ],
        ];
    }
}
