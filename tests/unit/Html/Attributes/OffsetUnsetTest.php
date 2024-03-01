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

namespace Phalcon\Tests\Unit\Html\Attributes;

use Phalcon\Html\Attributes;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class OffsetUnsetTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Html\Attributes :: offsetUnset()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-06-02
     */
    public function testHtmlAttributesOffsetUnset(): void
    {
        $data = [
            'type'  => 'text',
            'class' => 'form-control',
            'name'  => 'q',
            'value' => '',
        ];

        $attributes = new Attributes($data);

        $this->assertSame(
            $data,
            $attributes->toArray()
        );


        unset($attributes['class']);

        $expected = [
            'type'  => 'text',
            'name'  => 'q',
            'value' => '',
        ];

        $this->assertSame(
            $expected,
            $attributes->toArray()
        );
    }
}
