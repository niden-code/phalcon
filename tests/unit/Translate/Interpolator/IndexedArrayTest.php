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

namespace Phalcon\Tests\Unit\Translate\Interpolator;

use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\Interpolator\AssociativeArray;
use Phalcon\Translate\Interpolator\IndexedArray;
use Phalcon\Translate\InterpolatorFactory;
use PHPUnit\Framework\TestCase;
use UnitTester;

final class IndexedArrayTest extends TestCase
{
    /**
     * Tests Phalcon\Translate\Interpolator\IndexedArray :: objects
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateInterpolatorIndexedArrayInterpolator(): void
    {
        $language = [
            'Hello!'          => 'Привет!',
            'Hello %s %s %s!' => 'Привет, %s %s %s!',
        ];

        $params = [
            'content'             => $language,
            'defaultInterpolator' => 'indexedArray',
        ];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            $params
        );

        $actual = $translator->_(
            'Hello %s %s %s!',
            [
                'John',
                'D.',
                'Doe',
            ]
        );

        $expected = 'Привет, John D. Doe!';
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Interpolator\IndexedArray ::
     * replacePlaceholders()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateInterpolatorIndexedarrayReplacePlaceholders(): void
    {
        $interpolator = new IndexedArray();

        $actual = $interpolator->replacePlaceholders(
            'Hello, %s %s %s!',
            [
                'John',
                'D.',
                'Doe',
            ]
        );

        $expected = 'Hello, John D. Doe!';
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Interpolator\IndexedArray ::
     * replacePlaceholders() with no placeholders
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateInterpolatorIndexedarrayReplacePlaceholdersWithNoPlaceholders(): void
    {
        $interpolator = new IndexedArray();

        $source = 'Hello, %s %s %s!';
        $expected = $source;
        $actual = $interpolator->replacePlaceholders('Hello, %s %s %s!', []);
        $this->assertSame($expected, $actual);
    }
}
