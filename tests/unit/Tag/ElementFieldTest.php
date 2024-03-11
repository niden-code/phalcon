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

namespace Phalcon\Tests\Unit\Tag;

final class ElementFieldTest extends AbstractTagTestCase
{
    /**
     * @return array[]
     */
    public static function providerElements(): array
    {
        return [
            [
                'colorField',
                'color',
            ],
            [
                'dateField',
                'date',
            ],
            [
                'dateTimeField',
                'datetime',
            ],
            [
                'dateTimeLocalField',
                'datetime-local',
            ],
            [
                'emailField',
                'email',
            ],
            [
                'fileField',
                'file',
            ],
            [
                'hiddenField',
                'hidden',
            ],
            [
                'monthField',
                'month',
            ],
            [
                'numericField',
                'number',
            ],
            [
                'passwordField',
                'password',
            ],
            [
                'rangeField',
                'range',
            ],
            [
                'searchField',
                'search',
            ],
            [
                'telField',
                'tel',
            ],
            [
                'textField',
                'text',
            ],
            [
                'timeField',
                'time',
            ],
            [
                'urlField',
                'url',
            ],
            [
                'weekField',
                'week',
            ],
        ];
    }

    /**
     * Tests Phalcon\Tag :: weekField() - array as a parameter
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldArrayParameter(
        string $function,
        string $inputType
    ): void {
        $options = [
            'x_name',
            'class' => 'x_class',
        ];

        $expected = '<input type="' . $inputType . '" id="x_name" name="x_name" class="x_class"';

        $this->testFieldParameter($function, $options, $expected);
        $this->testFieldParameter($function, $options, $expected, true);
    }

    /**
     * Tests Phalcon\Tag :: weekField() - array as a parameters and id in it
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldArrayParameterWithId(
        string $function,
        string $inputType
    ): void {
        $options = [
            'x_name',
            'id'    => 'x_id',
            'class' => 'x_class',
            'size'  => '10',
        ];

        $expected = '<input type="' . $inputType . '" id="x_id" name="x_name" '
            . 'class="x_class" size="10"';

        $this->testFieldParameter($function, $options, $expected);
        $this->testFieldParameter($function, $options, $expected, true);
    }

    /**
     * Tests Phalcon\Tag :: weekField() - name and no id in parameter
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldArrayParameterWithNameNoId(
        string $function,
        string $inputType
    ): void {
        $options = [
            'x_name',
            'name'  => 'x_other',
            'class' => 'x_class',
            'size'  => '10',
        ];

        $expected = '<input type="' . $inputType . '" id="x_name" name="x_other" class="x_class" size="10"';

        $this->testFieldParameter($function, $options, $expected);
        $this->testFieldParameter($function, $options, $expected, true);
    }

    /**
     * Tests Phalcon\Tag :: string as a parameter
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldStringParameter(
        string $function,
        string $inputType
    ): void {
        $options  = 'x_name';
        $expected = '<input type="' . $inputType . '" id="x_name" name="x_name"';

        $this->testFieldParameter($function, $options, $expected);
        $this->testFieldParameter($function, $options, $expected, true);
    }

    /**
     * Tests Phalcon\Tag :: weekField() - displayTo
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldWithDisplayTo(
        string $function,
        string $inputType
    ): void {
        $options = [
            'x_name',
            'name'  => 'x_other',
            'class' => 'x_class',
            'size'  => '10',
        ];

        $expected = '<input type="' . $inputType . '" id="x_name" '
            . 'name="x_other" value="x_value" class="x_class" size="10"';

        $this->testFieldParameter($function, $options, $expected, false, 'displayTo');
        $this->testFieldParameter($function, $options, $expected, true, 'displayTo');
    }

    /**
     * Tests Phalcon\Tag :: weekField() - displayTo and element not present
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldWithDisplayToElementNotPresent(
        string $function,
        string $inputType
    ): void {
        $options = [
            'x_name',
            'name'  => 'x_other',
            'class' => 'x_class',
            'size'  => '10',
        ];

        $expected = '<input type="' . $inputType . '" id="x_name" '
            . 'name="x_other" value="x_value" class="x_class" size="10"';

        $this->testFieldParameter($function, $options, $expected, false, 'displayTo');
        $this->testFieldParameter($function, $options, $expected, true, 'displayTo');
    }

    /**
     * Tests Phalcon\Tag :: weekField() - setDefault
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldWithSetDefault(
        string $function,
        string $inputType
    ): void {
        $options = [
            'x_name',
            'name'  => 'x_other',
            'class' => 'x_class',
            'size'  => '10',
        ];

        $expected = '<input type="' . $inputType . '" id="x_name" '
            . 'name="x_other" value="x_value" class="x_class" size="10"';

        $this->testFieldParameter($function, $options, $expected, false, 'setDefault');
        $this->testFieldParameter($function, $options, $expected, true, 'setDefault');
    }

    /**
     * Tests Phalcon\Tag :: weekField() - setDefault and element not present
     *
     * @dataProvider providerElements
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2014-09-05
     */
    public function testTagFieldWithSetDefaultElementNotPresent(
        string $function,
        string $inputType
    ): void {
        $options = [
            'x_name',
            'name'  => 'x_other',
            'class' => 'x_class',
            'size'  => '10',
        ];

        $expected = '<input type="' . $inputType . '" id="x_name" '
            . 'name="x_other" value="x_value" class="x_class" size="10"';

        $this->testFieldParameter($function, $options, $expected, false, 'setDefault');
        $this->testFieldParameter($function, $options, $expected, true, 'setDefault');
    }
}
