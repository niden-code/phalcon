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

namespace Phalcon\Tests\Unit\Support\Helper\Str;

use Phalcon\Support\Helper\Str\Random;
use PHPUnit\Framework\TestCase;

use function strlen;

final class RandomTest extends TestCase
{
    /**
     * @return int[][]
     */
    public static function providerDistinct(): array
    {
        return [
            [1],
            [10],
            [100],
            [200],
            [500],
            [1000],
            [2000],
            [3000],
            [4000],
            [5000],
        ];
    }

    /**
     * @return int[][]
     */
    public static function providerOneToTen(): array
    {
        return [
            [1],
            [2],
            [3],
            [4],
            [5],
            [6],
            [7],
            [8],
            [9],
            [10],
        ];
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: random() - alnum
     *
     * @dataProvider providerOneToTen
     *
     * @param int $length
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrRandomAlnum(
        int $length
    ): void {
        $object = new Random();
        $source = $object(Random::RANDOM_ALNUM, $length);

        $this->assertSame(
            1,
            preg_match('/[a-zA-Z0-9]+/', $source, $matches)
        );

        $this->assertSame($source, $matches[0]);
        $this->assertSame($length, strlen($source));
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: random() - alpha
     *
     * @dataProvider providerOneToTen
     *
     * @param int $length
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrRandomAlpha(
        int $length
    ): void {
        $object = new Random();
        $source = $object(Random::RANDOM_ALPHA, $length);

        $this->assertSame(
            1,
            preg_match('/[a-zA-Z]+/', $source, $matches)
        );

        $this->assertSame($source, $matches[0]);
        $this->assertSame($length, strlen($source));
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: random() - constants
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrRandomConstants(): void
    {
        $this->assertSame(0, Random::RANDOM_ALNUM);
        $this->assertSame(1, Random::RANDOM_ALPHA);
        $this->assertSame(2, Random::RANDOM_HEXDEC);
        $this->assertSame(3, Random::RANDOM_NUMERIC);
        $this->assertSame(4, Random::RANDOM_NOZERO);
        $this->assertSame(5, Random::RANDOM_DISTINCT);
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: random() - distinct type
     *
     * @dataProvider providerDistinct
     *
     * @param int $length
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrRandomDistinct(
        int $length
    ): void {
        $object = new Random();

        $source = $object(Random::RANDOM_DISTINCT, $length);

        $this->assertMatchesRegularExpression(
            '#^[2345679ACDEFHJKLMNPRSTUVWXYZ]+$#',
            $source
        );

        $this->assertSame($length, strlen($source));
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: random() - hexdec
     *
     * @dataProvider providerOneToTen
     *
     * @param int $length
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrRandomHexDec(
        int $length
    ): void {
        $object = new Random();

        $source = $object(Random::RANDOM_HEXDEC, $length);

        $this->assertSame(
            1,
            preg_match('/[a-f0-9]+/', $source, $matches)
        );

        $this->assertSame($source, $matches[0]);
        $this->assertSame($length, strlen($source));
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: random() - non zero
     *
     * @dataProvider providerOneToTen
     *
     * @param int $length
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrRandomNonZero(
        int $length
    ): void {
        $object = new Random();
        $source = $object(Random::RANDOM_NOZERO, $length);

        $this->assertSame(
            1,
            preg_match('/[1-9]+/', $source, $matches)
        );

        $this->assertSame($source, $matches[0]);
        $this->assertSame($length, strlen($source));
    }

    /**
     * Tests Phalcon\Support\Helper\Str :: random() - numeric
     *
     * @dataProvider providerOneToTen
     *
     * @param int $length
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testSupportHelperStrRandomNumeric(
        int $length
    ): void {
        $object = new Random();
        $source = $object(Random::RANDOM_NUMERIC, $length);

        $this->assertSame(
            1,
            preg_match('/[0-9]+/', $source, $matches)
        );

        $this->assertSame($source, $matches[0]);
        $this->assertSame($length, strlen($source));
    }
}
