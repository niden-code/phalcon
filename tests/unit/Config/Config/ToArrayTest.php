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

use Phalcon\Tests\Unit\Config\AbstractConfigTestCase;
use UnitTester;

final class ToArrayTest extends AbstractConfigTestCase
{
    /**
     * Tests Phalcon\Config\Config :: toArray()
     *
     * @dataProvider providerExamples
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-06-19
     */
    public function testConfigToArray(string $adapter): void
    {
        $config = $this->getConfig($adapter);

        $expected = $this->config;
        $actual   = $config->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Config\Adapter\Grouped :: toArray()
     *
     * @author kjdev
     * @since  2013-07-18
     */
    public function testConfigAdapterGroupedToArray(): void
    {
        $config  = $this->getConfig('Grouped');
        $options = $this->config;

        $options['test']['property2'] = 'something-else';

        $expected = $options;
        $actual   = $config->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Config\Adapter\Ini :: toArray()
     *
     * @author kjdev
     * @since  2013-07-18
     */
    public function testConfigAdapterIniToArray(): void
    {
        $this->config['database']['num1'] = false;
        $this->config['database']['num2'] = false;
        $this->config['database']['num3'] = false;
        $this->config['database']['num4'] = true;
        $this->config['database']['num5'] = true;
        $this->config['database']['num6'] = true;
        $this->config['database']['num7'] = null;
        $this->config['database']['num8'] = 123;
        $this->config['database']['num9'] = 123.45;

        $config = $this->getConfig('Ini');

        $expected = $this->config;
        $actual   = $config->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [''], // Config
            ['Json'],
            ['Php'],
            ['Yaml'],
        ];
    }
}
