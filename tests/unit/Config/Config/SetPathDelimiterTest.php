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

final class SetPathDelimiterTest extends AbstractConfigTestCase
{
    public static function providerExamples(): array
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
     * Tests Phalcon\Config\Config :: setPathDelimiter()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-06-19
     */
    public function testConfigSetPathDelimiter(string $adapter): void
    {
        $config   = $this->getConfig($adapter);
        $existing = $config->getPathDelimiter();

        $expected = 'yeah';
        $actual   = $config->path('test.parent.property2', 'Unknown');
        $this->assertSame($expected, $actual);

        $config->setPathDelimiter('/');

        $expected = 'Unknown';
        $actual   = $config->path('test.parent.property2', 'Unknown');
        $this->assertSame($expected, $actual);

        $expected = 'yeah';
        $actual   = $config->path('test/parent/property2', 'Unknown');
        $this->assertSame($expected, $actual);

        $config->setPathDelimiter($existing);
    }
}
