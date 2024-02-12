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

namespace Phalcon\Tests\Unit\Logger\Adapter\Syslog;

use Codeception\Example;
use Phalcon\Logger\Adapter\Syslog;
use PHPUnit\Framework\TestCase;

use function getProtectedProperty;

final class ConstructTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Syslog :: __construct()
     *
     * @dataProvider providerExamples
     *
     * @param array   $options
     * @param string  $property
     * @param int     $expected
     *
     * @param Example $example
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testLoggerAdapterSyslogConstructOptionsCast(
        array $options,
        string $property,
        int $expected
    ): void {
        $streamName = getNewFileName2('log');

        $adapter  = new Syslog($streamName, $options);
        $actual  = getProtectedProperty($adapter, $property);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                [],
                'option',
                LOG_ODELAY,
            ],
            [
                ['option' => LOG_ALERT | LOG_INFO],
                'option',
                LOG_ALERT | LOG_INFO,
            ],
            [
                [],
                'facility',
                LOG_USER,
            ],
            [
                ['facility' => LOG_DAEMON],
                'facility',
                LOG_DAEMON,
            ],
        ];
    }
}
