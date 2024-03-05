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

namespace Phalcon\Tests\Unit\Encryption\Security\Random;

use Phalcon\Encryption\Security\Random;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class Base64SafeTest extends AbstractUnitTestCase
{
    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                2,
                false,
                'a-z0-9_-',
            ],
            [
                2,
                true,
                'a-z0-9_=-',
            ],
            [
                12,
                false,
                'a-z0-9_-',
            ],
            [
                12,
                true,
                'a-z0-9_=-',
            ],
            [
                16,
                false,
                'a-z0-9_-',
            ],
            [
                16,
                true,
                'a-z0-9_=-',
            ],
            [
                24,
                false,
                'a-z0-9_-',
            ],
            [
                24,
                true,
                'a-z0-9_=-',
            ],
            [
                48,
                false,
                'a-z0-9_-',
            ],
            [
                48,
                true,
                'a-z0-9_=-',
            ],
            [
                100,
                false,
                'a-z0-9_-',
            ],
            [
                100,
                true,
                'a-z0-9_=-',
            ],
        ];
    }

    /**
     * Tests Phalcon\Encryption\Security\Random :: base64Safe()
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     *
     * @dataProvider providerExamples
     */
    public function testEncryptionSecurityRandomBase64Safe(
        int $len,
        bool $padding,
        string $pattern
    ): void {
        $random = new Random();

        $expected = '#^[' . $pattern . ']+$#i';
        $actual   = $random->base64Safe($len, $padding);
        $this->assertMatchesRegularExpression($expected, $actual);
    }
}
