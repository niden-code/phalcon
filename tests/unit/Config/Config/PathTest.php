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

final class PathTest extends AbstractConfigTestCase
{
    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [''], // Config
            ['Ini'],
            ['Json'],
            ['Php'],
            ['Yaml'],
        ];
    }

    /**
     * @return array[]
     */
    public static function providerExamplesDefault(): array
    {
        return [
            [''], // Config
            ['Grouped'],
            ['Ini'],
            ['Json'],
            ['Php'],
            ['Yaml'],
        ];
    }

    /**
     * Tests Phalcon\Config\Adapter\Grouped :: path()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testConfigAdapterGroupedPath(): void
    {
        $config = $this->getConfig('Grouped');

        $expected = 2;
        $actual   = $config->path('test');
        $this->assertCount($expected, $actual);

        $expected = 'something-else';
        $actual   = $config->path('test.property2');
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Config\Config :: path()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-06-19
     */
    public function testConfigPath(string $adapter): void
    {
        $config = $this->getConfig($adapter);

        $expected = 1;
        $actual   = $config->path('test');
        $this->assertCount($expected, $actual);

        $expected = 'yeah';
        $actual   = $config->path('test.parent.property2');
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Config\Config :: path() - default
     *
     * @dataProvider providerExamplesDefault
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-06-19
     */
    public function testConfigPathDefault(string $adapter): void
    {
        $config = $this->getConfig($adapter);

        $expected = 'Unknown';
        $actual   = $config->path('test.parent.property3', 'Unknown');
        $this->assertSame($expected, $actual);
    }
}
