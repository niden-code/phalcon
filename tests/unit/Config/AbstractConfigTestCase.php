<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Tests\Unit\Config;

use Phalcon\Config\Adapter\Grouped;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Config\Adapter\Json;
use Phalcon\Config\Adapter\Php;
use Phalcon\Config\Adapter\Yaml;
use Phalcon\Config\Config;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function is_array;

abstract class AbstractConfigTestCase extends AbstractUnitTestCase
{
    /**
     * @var array
     */
    protected array $config = [
        'phalcon'     => [
            'baseuri' => '/phalcon/',
        ],
        'models'      => [
            'metadata' => 'memory',
        ],
        'database'    => [
            'adapter'  => 'mysql',
            'host'     => 'localhost',
            'username' => 'user',
            'password' => 'passwd',
            'name'     => 'demo',
        ],
        'test'        => [
            'parent' => [
                'property'      => 1,
                'property2'     => 'yeah',
                'emptyProperty' => '',
            ],
        ],
        'issue-12725' => [
            'channel' => [
                'handlers' => [
                    0 => [
                        'name'           => 'stream',
                        'level'          => 'debug',
                        'fingersCrossed' => 'info',
                        'filename'       => 'channel.log',
                    ],
                    1 => [
                        'name'           => 'redis',
                        'level'          => 'debug',
                        'fingersCrossed' => 'info',
                    ],
                ],
            ],
        ],
    ];

//    /**
//     * Tests Phalcon\Config\Adapter\* :: __construct()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkConstruct(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $this->compareConfig($this->config, $config);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: count()
//     *
//     * @author Faruk Brbovic <fbrbovic@devstub.com>
//     * @since  2014-11-03
//     */
//    protected function checkCount(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $expected = 5;
//        $actual   = $config->count();
//        $this->assertSame($expected, $actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: get()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkGet(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $expected = 'memory';
//        $actual   = $config->get('models')
//                           ->get('metadata')
//        ;
//        $this->assertSame($expected, $actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: getPathDelimiter()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkGetPathDelimiter(string $adapter = '')
//    {
//        $config   = $this->getConfig($adapter);
//        $existing = $config->getPathDelimiter();
//
//        $expected = '.';
//        $actual   = $config->getPathDelimiter();
//        $this->assertSame($expected, $actual);
//
//
//        $config->setPathDelimiter('/');
//
//        $expected = '/';
//        $actual   = $config->getPathDelimiter();
//        $this->assertSame($expected, $actual);
//
//        $config->setPathDelimiter($existing);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: merge() - exception
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2019-06-19
//     */
//    protected function checkMergeException(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//        $this->expectThrowable(
//            new Exception(
//                'Invalid data type for merge.'
//            ),
//            function () use ($config) {
//                $config->merge(false);
//            }
//        );
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: offsetExists()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkOffsetExists(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $actual = $config->offsetExists('models');
//        $this->assertTrue($actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: offsetGet()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkOffsetGet(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $expected = 'memory';
//        $actual   = $config->offsetGet('models')
//                           ->offsetGet('metadata')
//        ;
//        $this->assertSame($expected, $actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: offsetSet()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkOffsetSet(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//        $config->offsetSet('models', 'something-else');
//
//        $expected = 'something-else';
//        $actual   = $config->offsetGet('models');
//        $this->assertSame($expected, $actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: offsetUnset()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkOffsetUnset(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $actual = $config->offsetExists('database');
//        $this->assertTrue($actual);
//
//        $config->offsetUnset('database');
//
//        $actual = $config->offsetExists('database');
//        $this->assertFalse($actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: path()
//     *
//     * @author michanismus
//     * @since  2017-03-29
//     */
//    protected function checkPath(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $expected = 1;
//        $actual   = $config->path('test');
//        $this->assertCount($expected, $actual);
//
//        $expected = 'yeah';
//        $actual   = $config->path('test.parent.property2');
//        $this->assertSame($expected, $actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: path() - default
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkPathDefault(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $expected = 'Unknown';
//        $actual   = $config->path('test.parent.property3', 'Unknown');
//        $this->assertSame($expected, $actual);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: setPathDelimiter()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkSetPathDelimiter(string $adapter = '')
//    {
//        $config   = $this->getConfig($adapter);
//        $existing = $config->getPathDelimiter();
//
//        $expected = 'yeah';
//        $actual   = $config->path('test.parent.property2', 'Unknown');
//        $this->assertSame($expected, $actual);
//
//        $config->setPathDelimiter('/');
//
//        $expected = 'Unknown';
//        $actual   = $config->path('test.parent.property2', 'Unknown');
//        $this->assertSame($expected, $actual);
//
//        $expected = 'yeah';
//        $actual   = $config->path('test/parent/property2', 'Unknown');
//        $this->assertSame($expected, $actual);
//
//        $config->setPathDelimiter($existing);
//    }
//
//    /**
//     * Tests Phalcon\Config\Adapter\* :: toArray()
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2018-11-13
//     */
//    protected function checkToArray(string $adapter = '')
//    {
//        $config = $this->getConfig($adapter);
//
//        $expected = $this->config;
//        $actual   = $config->toArray();
//        $this->assertSame($expected, $actual);
//    }

    protected function compareConfig(array $actual, Config $expected)
    {
        $this->assertSame($expected->toArray(), $actual);

        foreach ($actual as $key => $value) {
            $this->assertTrue(isset($expected->$key));

            if (is_array($value)) {
                $this->compareConfig($value, $expected->$key);
            }
        }
    }

    /**
     * Returns a config object
     *
     * @return Config|Ini|Json|Php|Yaml
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    protected function getConfig(string $adapter = '')
    {
        switch ($adapter) {
            case 'Ini':
                return new Ini(
                    self::dataDir('assets/config/config.ini')
                );

            case 'Json':
                return new Json(
                    self::dataDir('assets/config/config.json')
                );

            case 'Php':
                return new Php(
                    self::dataDir('assets/config/config.php')
                );

            case 'Yaml':
                return new Yaml(
                    self::dataDir('assets/config/config.yml')
                );

            case 'Grouped':
                $config = [
                    self::dataDir('assets/config/config.php'),
                    [
                        'adapter'  => 'json',
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

                return new Grouped($config);

            default:
                return new Config($this->config);
        }
    }
}
