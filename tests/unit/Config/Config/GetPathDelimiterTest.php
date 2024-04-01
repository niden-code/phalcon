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

final class GetPathDelimiterTest extends AbstractConfigTestCase
{
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
     * Tests Phalcon\Config\Config :: getPathDelimiter()
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2021-10-21
     */
    public function testConfigGetPathDelimiter(string $adapter): void
    {
        $config   = $this->getConfig($adapter);
        $existing = $config->getPathDelimiter();

        $expected = '.';
        $actual   = $config->getPathDelimiter();
        $this->assertSame($expected, $actual);


        $config->setPathDelimiter('/');

        $expected = '/';
        $actual   = $config->getPathDelimiter();
        $this->assertSame($expected, $actual);

        $config->setPathDelimiter($existing);
    }
}
