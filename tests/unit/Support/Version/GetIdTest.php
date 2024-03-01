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

namespace Phalcon\Tests\Unit\Support\Version;

use Phalcon\Tests1\Fixtures\Support\Version\VersionAlphaFixture;
use Phalcon\Tests1\Fixtures\Support\Version\VersionBetaFixture;
use Phalcon\Tests1\Fixtures\Support\Version\VersionRcFixture;
use Phalcon\Tests1\Fixtures\Support\Version\VersionStableFixture;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function is_string;

final class GetIdTest extends AbstractUnitTestCase
{
    /**
     * @return string[][]
     */
    public static function providerGetId(): array
    {
        return [
            [
                VersionAlphaFixture::class,
                '5000011',
            ],
            [
                VersionBetaFixture::class,
                '5000022',
            ],
            [
                VersionRcFixture::class,
                '5000033',
            ],
            [
                VersionStableFixture::class,
                '5000000',
            ],
        ];
    }

    /**
     * Tests getId()
     *
     * @dataProvider providerGetId
     *
     * @param string $method
     * @param string $expected
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportVersionGetId(
        string $method,
        string $expected
    ): void {
        $version = new $method();

        $actual = $version->getId();
        $this->assertTrue(is_string($actual));
        $this->assertSame($expected, $actual);
    }
}
