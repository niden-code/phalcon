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

use Phalcon\Translate\Interpolator\AssociativeArray;
use PHPUnit\Framework\TestCase;

final class AssociativeArrayTest extends TestCase
{
    /**
     * Tests Phalcon\Translate\Interpolator\AssociativeArray ::
     * replacePlaceholders()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateInterpolatorAssociativearrayReplacePlaceholders(): void
    {
        $interpolator = new AssociativeArray();

        $stringFrom = 'Hello, %fname% %mname% %lname%!';

        $actual = $interpolator->replacePlaceholders(
            $stringFrom,
            [
                'fname' => 'John',
                'lname' => 'Doe',
                'mname' => 'D.',
            ]
        );

        $expected = 'Hello, John D. Doe!';
        $this->assertSame($expected, $actual);
    }
}
