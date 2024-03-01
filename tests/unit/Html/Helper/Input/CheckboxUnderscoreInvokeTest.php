<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Input;

use Codeception\Example;
use Phalcon\Html\Escaper;
use Phalcon\Html\Helper\Input\Checkbox;
use Phalcon\Html\Helper\Input\Radio;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function sprintf;

final class CheckboxUnderscoreInvokeTest extends AbstractUnitTestCase
{
    /**
     * @param string $type
     *
     * @return array
     */
    public static function providerExamples(string $type): array
    {
        return [
            [
                'x_name',
                null,
                [],
                null,
                '<input type="'
                . $type
                . '" id="x_name" name="x_name" />',
            ],
            [
                'x_name',
                null,
                [],
                [],
                '<label for="x_name"><input type="'
                . $type
                . '" id="x_name" name="x_name" /></label>',
            ],
            [
                'x_name',
                null,
                [
                    'id' => 'x_id',
                ],
                [],
                '<label for="x_id"><input type="'
                . $type
                . '" id="x_id" name="x_name" /></label>',
            ],
            [
                'x_name',
                null,
                [
                    'id' => 'x_id',
                ],
                [
                    "text" => "some text",
                ],
                '<label for="x_id"><input type="'
                . $type
                . '" id="x_id" name="x_name" />some text</label>',
            ],
            [
                'x_name',
                null,
                [
                    'id'        => 'x_id',
                    'unchecked' => 'no',
                ],
                [
                    "text" => "some text",
                ],
                '<hidden name="x_name" value="no">' .
                '<label for="x_id"><input type="'
                . $type
                . '" id="x_id" name="x_name" />some text</label>',
            ],
            [
                'x_name',
                "yes",
                [
                    'id'        => 'x_id',
                    'unchecked' => 'no',
                    'checked'   => 'yes',
                ],
                [
                    "text" => "some text",
                ],
                '<hidden name="x_name" value="no">' .
                '<label for="x_id">' .
                '<input type="'
                . $type
                . '" id="x_id" name="x_name" value="yes" checked="checked" />some text</label>',
            ],
            [
                'x_name',
                "yes",
                [
                    'id' => 'x_id',
                ],
                [
                    "text" => "some text",
                ],
                '<label for="x_id">' .
                '<input type="'
                . $type
                . '" id="x_id" name="x_name" value="yes" />some text</label>',
            ],
        ];
    }

    /**
     * @return array
     */
    public static function providerExamplesCheckbox(): array
    {
        return self::providerExamples('checkbox');
    }

    /**
     * @return array
     */
    public static function providerExamplesRadio(): array
    {
        return self::providerExamples('radio');
    }

    /**
     * Tests Phalcon\Html\Helper\Input\Checkbox :: __invoke()
     *
     * @dataProvider providerExamplesCheckbox
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperInputCheckboxUnderscoreInvoke(
        string $name,
        ?string $value,
        array $attributes,
        ?array $label,
        string $render
    ): void {
        $escaper = new Escaper();
        $helper  = new Checkbox($escaper);

        $result = $helper($name, $value, $attributes);

        if (null !== $label) {
            $result->label($label);
        }

        $this->assertSame(sprintf($render, $render), (string)$result);
    }

    /**
     * Tests Phalcon\Html\Helper\Input\Checkbox :: __invoke()
     *
     * @dataProvider providerExamplesRadio
     *
     * @param Example $example
     *
     * @return void
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperInputRadioUnderscoreInvoke(
        string $name,
        ?string $value,
        array $attributes,
        ?array $label,
        string $render
    ): void {
        $escaper = new Escaper();
        $helper  = new Radio($escaper);

        $result = $helper($name, $value, $attributes);

        if (null !== $label) {
            $result->label($label);
        }

        $this->assertSame(sprintf($render, $render), (string)$result);
    }
}
