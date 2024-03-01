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

namespace Phalcon\Tests\Unit\Annotations\Annotation;

use Phalcon\Annotations\Annotation;
use Phalcon\Annotations\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function uniqid;

final class GetExpressionTest extends AbstractUnitTestCase
{
    private int $PHANNOT_T_ANNOTATION = 300;
    private int $PHANNOT_T_STRING     = 303;

    /**
     * Tests Phalcon\Annotations\Annotation :: getExpression()
     *
     * @author Jeremy PASTOURET <https://github.com/jenovateurs>
     * @since  2020-01-22
     */
    public function testAnnotationsAnnotationGetExpression(): void
    {
        $value1 = uniqid('tst-');
        $value2 = uniqid('tst-');
        $value3 = uniqid('tst-');

        $expr1 = [
            'type'  => $this->PHANNOT_T_STRING,
            'value' => $value1,
        ];

        $expr2 = [
            'type'  => $this->PHANNOT_T_STRING,
            'value' => $value2,
        ];

        $expr3 = [
            'type'  => $this->PHANNOT_T_ANNOTATION,
            'value' => $value3,
        ];

        $expr4 = [
            'type'  => 99999,
            'value' => $value3,
        ];

        $expr = [
            [
                'expr' => $expr1,
            ],
            [
                'expr' => $expr2,
            ],
            [
                'expr' => $expr3,
            ],
        ];

        $annotation = new Annotation(
            [
                'name'      => 'NovAnnotation',
                'arguments' => $expr,
            ]
        );

        $expected = $value1;
        $actual   = $annotation->getExpression($expr1);
        $this->assertSame($expected, $actual);

        $expected = $value2;
        $actual   = $annotation->getExpression($expr2);
        $this->assertSame($expected, $actual);

        $expected = Annotation::class;
        $actual   = $annotation->getExpression($expr3);
        $this->assertInstanceOf($expected, $actual);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The expression 99999 is unknown');
        $expr = [
            [
                'expr' => $expr4,
            ],
        ];

        $annotation = new Annotation(
            [
                'name'      => 'NovAnnotation',
                'arguments' => $expr,
            ]
        );

        $actual = $annotation->getExpression($expr4);
    }
}
