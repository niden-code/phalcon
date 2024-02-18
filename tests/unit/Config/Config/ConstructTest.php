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

use Codeception\Stub;
use Phalcon\Config\Adapter\Grouped;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Config\Adapter\Yaml;
use Phalcon\Config\Config;
use Phalcon\Config\Exception;
use Phalcon\Tests\Unit\Config\AbstractConfigTestCase;

use Phalcon\Tests1\Fixtures\Config\Adapter\IniCannotReadFixture;

use Phalcon\Tests1\Fixtures\Config\Adapter\YamlExtensionLoadedFixture;

use Phalcon\Tests1\Fixtures\Config\Adapter\YamlParseFileFixture;

use function basename;
use function dataDir2;
use function define;
use function hash;

use const INI_SCANNER_NORMAL;
use const PATH_DATA2;

final class ConstructTest extends AbstractConfigTestCase
{
    /**
     * Tests Phalcon\Config\Adapter\* :: __construct()
     *
     * @dataProvider providerExamples
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCheckConstruct(string $adapter): void
    {
        $config = $this->getConfig($adapter);

        $this->compareConfig($this->config, $config);
    }

    /**
     * Tests Phalcon\Config\Adapter\Yaml :: __construct() - callbacks
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-21
     */
    public function testConfigAdapterYamlConstructCallbacks(): void
    {
        $config = new Yaml(
            dataDir2('assets/config/callbacks.yml'),
            [
                '!decrypt' => function ($value) {
                    return hash('sha256', $value);
                },
                '!approot' => function ($value) {
                    return PATH_DATA2 . $value;
                },
            ]
        );

        $expected = PATH_DATA2 . '/app/controllers/';
        $actual   = $config->application->controllersDir;
        $this->assertSame($expected, $actual);

        $expected = '9f7030891b235f3e06c4bff74ae9dc1b9b59d4f2e4e6fd94eeb2b91caee5d223';
        $actual   = $config->database->password;
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Config\Adapter\Yaml :: __construct() -
     * exception extension loaded
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-21
     */
    public function testConfigAdapterYamlConstructExceptionExtensionLoaded(): void
    {
        $filePath = dataDir2('assets/config/callbacks.yml');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Yaml extension is not loaded');

        $adapter = new YamlExtensionLoadedFixture($filePath);
    }

    /**
     * Tests Phalcon\Config\Adapter\Yaml :: __construct() -
     * exception file cannot be parsed
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-21
     */
    public function testConfigAdapterYamlConstructExceptionFileCannotBeParsed(): void
    {
        $filePath = dataDir2('assets/config/callbacks.yml');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Configuration file ' . basename($filePath) . ' cannot be loaded'
        );

        $adapter = new YamlParseFileFixture($filePath);
    }

    /**
     * Tests Phalcon\Config\Adapter\Ini :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testConfigAdapterIniConstruct()
    {
        $this->config['database']['num1'] = false;
        $this->config['database']['num2'] = false;
        $this->config['database']['num3'] = false;
        $this->config['database']['num4'] = true;
        $this->config['database']['num5'] = true;
        $this->config['database']['num6'] = true;
        $this->config['database']['num7'] = null;
        $this->config['database']['num8'] = 123;
        $this->config['database']['num9'] = (float)123.45;

        $config = $this->getConfig('Ini');

        $this->compareConfig($this->config, $config);
    }

    /**
     * Tests Phalcon\Config\Adapter\Ini :: __construct() - constants
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testConfigAdapterIniConstructConstants()
    {
        define('TEST_CONST2', 'foo');

        $config = new Ini(
            dataDir2('assets/config/config-with-constants.ini'),
            INI_SCANNER_NORMAL
        );

        $expected = [
            'test'    => 'foo',
            'path'    => 'foo/something/else',
            'section' => [
                'test'      => 'foo',
                'path'      => 'foo/another-thing/somewhere',
                'parent'    => [
                    'property'  => 'foo',
                    'property2' => 'foohello',
                ],
                'testArray' => [
                    'value1',
                    'value2',
                ],
            ],

        ];

        $actual = $config->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Config\Adapter\Ini :: __construct() - exceptions
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-26
     */
    public function testConfigAdapterIniConstructExceptions()
    {
        $filePath = dataDir2('assets/config/config-with-constants.ini');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Configuration file ' . basename($filePath) . ' cannot be loaded'
        );

        $adapter = new IniCannotReadFixture($filePath);
    }

    /**
     * Tests Phalcon\Config\Adapter\Grouped :: __construct() - complex instance
     *
     * @author fenikkusu
     * @since  2017-06-06
     */
    public function testConfigAdapterGroupedConstructComplexInstance()
    {
        $this->config['test']['property2'] = 'something-else';
        $this->config['test']['property']  = 'blah';

        $config = [
            dataDir2('assets/config/config.php'),
            [
                'adapter' => 'json',
                'filePath' => dataDir2('assets/config/config.json'),
            ],
            [
                'adapter' => 'array',
                'config'  => [
                    'test' => [
                        'property2' => 'something-else',
                    ],
                ],
            ],
            new Config(
                [
                    'test' => [
                        'property' => 'blah',
                    ],
                ]
            ),
        ];

        foreach ([[], ['']] as $parameters) {
            $this->compareConfig(
                $this->config,
                new Grouped($config, ...$parameters)
            );
        }
    }

    /**
     * Tests Phalcon\Config\Adapter\Grouped :: __construct() - default adapter
     *
     * @author fenikkusu
     * @since  2017-06-06
     */
    public function testConfigAdapterGroupedConstructDefaultAdapter()
    {
        $this->config['test']['property2'] = 'something-else';

        $config = [
            [
                'filePath' => dataDir2('assets/config/config.json'),
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

        $object = new Grouped($config, 'json');

        $this->compareConfig($this->config, $object);
    }

    /**
     * Tests Phalcon\Config\Adapter\Grouped :: __construct() - exception
     *
     * @author Fenikkusu
     * @since  2017-06-06
     */
    public function testConfigAdapterGroupedConstructThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "To use 'array' adapter you have to specify the 'config' as an array."
        );

        $adapter = new Grouped(
            [
                [
                    'adapter' => 'array',
                ],
            ]
        );
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [''], // Group
            ['Json'],
            ['Php'],
            ['Yaml'],
        ];
    }
}
