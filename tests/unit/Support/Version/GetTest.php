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

use Phalcon\Tests\Fixtures\Version\VersionAlphaFixture;
use Phalcon\Tests\Fixtures\Version\VersionBetaFixture;
use Phalcon\Tests\Fixtures\Version\VersionRcFixture;
use Phalcon\Tests\Fixtures\Version\VersionStableFixture;
use PHPUnit\Framework\TestCase;

use function is_string;

final class GetTest extends TestCase
{
    /**
     * @return string[][]
     */
    public static function providerGet(): array
    {
        return [
            [
                VersionAlphaFixture::class,
                '5.0.0alpha1',
            ],
            [
                VersionBetaFixture::class,
                '5.0.0beta2',
            ],
            [
                VersionRcFixture::class,
                '5.0.0RC3',
            ],
            [
                VersionStableFixture::class,
                '5.0.0',
            ],
        ];
    }

    /**
     * Tests get()
     *
     * @dataProvider providerGet
     *
     * @param string $method
     * @param string $expected
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportVersionGet(
        string $method,
        string $expected
    ): void {
        $version = new $method();

        $actual = $version->get();
        $this->assertTrue(is_string($actual));
        $this->assertSame($expected, $actual);
    }
}
