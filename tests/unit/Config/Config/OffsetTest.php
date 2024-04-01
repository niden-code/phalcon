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

final class OffsetTest extends AbstractConfigTestCase
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
     * Tests Phalcon\Config\Config :: offset*
     *
     * @dataProvider providerExamples
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-06-19
     */
    public function testConfigOffsetExistsUnset(string $adapter): void
    {
        $config = $this->getConfig($adapter);

        $actual = $config->offsetExists('database');
        $this->assertTrue($actual);

        $expected = 'memory';
        $actual   = $config->offsetGet('models')
                           ->offsetGet('metadata')
        ;
        $this->assertSame($expected, $actual);

        $config->offsetSet('models', 'something-else');
        $expected = 'something-else';
        $actual   = $config->offsetGet('models');
        $this->assertSame($expected, $actual);

        $config->offsetUnset('models');

        $actual = $config->offsetExists('models');
        $this->assertFalse($actual);
    }
}
