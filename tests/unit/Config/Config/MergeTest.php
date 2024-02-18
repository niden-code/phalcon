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

namespace Phalcon\Tests\Unit\Config\Config;

use Codeception\Example;
use Phalcon\Config\Config;
use Phalcon\Config\Exception;
use Phalcon\Tests\Fixtures\Traits\ConfigTrait;
use Phalcon\Tests\Unit\Config\AbstractConfigTestCase;
use UnitTester;

final class MergeTest extends AbstractConfigTestCase
{
    /**
     * Tests Phalcon\Config\Config :: merge()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-02-15
     */
    public function testConfigMergeConfig(): void
    {
        $config = $this->getConfig();

        $expected = $this->getMergedByConfig();
        $actual   = $config;
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests Phalcon\Config\Config :: merge()
     *
     * @dataProvider providerMergeConfigs
     *
     * @link         https://github.com/phalcon/cphalcon/issues/13351
     * @link         https://github.com/phalcon/cphalcon/issues/13201
     * @link         https://github.com/phalcon/cphalcon/issues/13768
     * @link         https://github.com/phalcon/cphalcon/issues/12779
     * @link         https://github.com/phalcon/phalcon/issues/196
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2121-10-21
     */
    public function testConfigMergeConfigCases(
        array $source,
        array $target,
        array $expected
    ): void {
        $sourceConfig = new Config($source);
        $targetConfig = new Config($target);

        /**
         * As Config object
         */
        $actual = $sourceConfig
            ->merge($targetConfig)
            ->toArray()
        ;
        $this->assertSame($expected, $actual);

        $targetArray = $targetConfig->toArray();

        /**
         * As array
         */
        $actual = $sourceConfig
            ->merge($targetArray)
            ->toArray()
        ;
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Config :: merge() - exceptions
     *
     * @dataProvider providerExamples
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-26
     */
    public function testConfigMergeExceptions(string $adapter): void
    {
        $config = $this->getConfig($adapter);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid data type for merge.');

        $config->merge('invalid-config');
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [''], // Group
            ['Grouped'],
            ['Ini'],
            ['Json'],
            ['Php'],
            ['Yaml'],
        ];
    }

    /**
     * @return array<array-key, array<string, mixed>>
     */
    public static function providerMergeConfigs(): array
    {
        return [
            [
                [
                    'a' => 'aaa',
                    'b' => [
                        'bar' => 'rrr',
                        'baz' => 'zzz',
                    ],
                ],
                [
                    'c' => 'ccc',
                    'b' => [
                        'baz' => 'xxx',
                    ],
                ],
                [
                    'a' => 'aaa',
                    'b' => [
                        'bar' => 'rrr',
                        'baz' => 'xxx',
                    ],
                    'c' => 'ccc',
                ],
            ],
            [
                [
                    '1' => 'aaa',
                    '2' => [
                        '11' => 'rrr',
                        '12' => 'zzz',
                    ],
                ],
                [
                    '3' => 'ccc',
                    '2' => [
                        '12' => 'xxx',
                    ],
                ],
                [
                    '1' => 'aaa',
                    '2' => [
                        '11' => 'rrr',
                        '12' => 'xxx',
                    ],
                    '3' => 'ccc',
                ],
            ],
            [
                [
                    '0.4' => 0.4,
                ],
                [
                    '1.1'                => 1,
                    '1.2'                => 1.2,
                    '2.8610229492188E-6' => 3,
                ],
                [
                    '0.4'                => 0.4,
                    '1.1'                => 1,
                    '1.2'                => 1.2,
                    '2.8610229492188E-6' => 3,
                ],
            ],
            [
                [
                    1 => 'Apple',
                ],
                [
                    2 => 'Banana',
                ],
                [
                    1 => 'Apple',
                    2 => 'Banana',
                ],
            ],
            [
                [
                    0 => 'Apple',
                ],
                [
                    2 => 'Banana',
                ],
                [
                    0 => 'Apple',
                    2 => 'Banana',
                ],
            ],
            [
                [
                    1   => 'Apple',
                    'p' => 'Pineapple',
                ],
                [
                    2 => 'Banana',
                ],
                [
                    1   => 'Apple',
                    'p' => 'Pineapple',
                    2   => 'Banana',
                ],
            ],
            [
                [
                    'One' => [
                        1   => 'Apple',
                        'p' => 'Pineapple',
                    ],
                    'Two' => [
                        1 => 'Apple',
                    ],
                ],
                [
                    'One' => [
                        2 => 'Banana',
                    ],
                    'Two' => [
                        2 => 'Banana',
                    ],
                ],
                [
                    'One' => [
                        1   => 'Apple',
                        'p' => 'Pineapple',
                        2   => 'Banana',
                    ],
                    'Two' => [
                        1 => 'Apple',
                        2 => 'Banana',
                    ],
                ],
            ],
            [
                [
                    'controllersDir' => '../x/y/z',
                    'modelsDir'      => '../x/y/z',
                    'database'       => [
                        'adapter'      => 'Mysql',
                        'host'         => 'localhost',
                        'username'     => 'scott',
                        'password'     => 'cheetah',
                        'name'         => 'test_db',
                        'charset'      => [
                            'primary' => 'utf8',
                        ],
                        'alternatives' => [
                            'primary' => 'latin1',
                            'second'  => 'latin1',
                        ],
                    ],
                ],
                [
                    'modelsDir' => '../x/y/z',
                    'database'  => [
                        'adapter'      => 'Postgresql',
                        'host'         => 'localhost',
                        'username'     => 'peter',
                        'options'      => [
                            'case'     => 'lower',
                            'encoding' => 'SET NAMES utf8',
                        ],
                        'alternatives' => [
                            'primary' => 'swedish',
                            'third'   => 'american',
                        ],
                    ],
                ],
                [
                    'controllersDir' => '../x/y/z',
                    'modelsDir'      => '../x/y/z',
                    'database'       => [
                        'adapter'      => 'Postgresql',
                        'host'         => 'localhost',
                        'username'     => 'peter',
                        'password'     => 'cheetah',
                        'name'         => 'test_db',
                        'charset'      => [
                            'primary' => 'utf8',
                        ],
                        'alternatives' => [
                            'primary' => 'swedish',
                            'second'  => 'latin1',
                            'third'   => 'american',
                        ],
                        'options'      => [
                            'case'     => 'lower',
                            'encoding' => 'SET NAMES utf8',
                        ],
                    ],
                ],
            ],
            [
                [
                    'keys' => [
                        '0' => 'scott',
                        '1' => 'cheetah',
                    ],
                ],
                [
                    'keys' => ['peter'],
                ],
                [
                    'keys' => [
                        '0' => 'peter',
                        '1' => 'cheetah',
                    ],
                ],
            ],
            [
                [
                    'keys' => [
                        'peter',
                    ],
                ],
                [
                    'keys' => [
                        'cheetah',
                        'scott',
                    ],
                ],
                [
                    'keys' => [
                        '0' => 'cheetah',
                        '1' => 'scott',
                    ],
                ],
            ],
            [
                [
                    'test'     => 123,
                    'empty'    => [],
                    'nonEmpty' => [
                        5 => 'test',
                    ],
                ],
                [
                    'empty' => [
                        3 => 'toEmpty',
                    ],
                ],
                [
                    'test'     => 123,
                    'empty'    => [
                        3 => 'toEmpty',
                    ],
                    'nonEmpty' => [
                        5 => 'test',
                    ],
                ],
            ],
            [
                [
                    'test'     => 123,
                    'empty'    => [],
                    'nonEmpty' => [
                        5 => 'test',
                    ],
                ],
                [
                    'nonEmpty' => [
                        3 => 'toNonEmpty',
                    ],
                ],
                [
                    'test'     => 123,
                    'empty'    => [],
                    'nonEmpty' => [
                        5 => 'test',
                        3 => 'toNonEmpty',
                    ],
                ],
            ],
        ];
    }

    /**
     * Merges the reference config object into an empty config object.
     *
     * @return Config
     * @throws Exception
     */
    private function getMergedByConfig(): Config
    {
        $config = new Config();
        $config->merge($this->getConfig());

        return $config;
    }
}
