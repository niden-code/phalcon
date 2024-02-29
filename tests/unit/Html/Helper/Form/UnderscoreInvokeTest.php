<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Form;

use Codeception\Example;
use Phalcon\Html\Escaper;
use Phalcon\Html\Exception;
use Phalcon\Html\Helper\Form;
use Phalcon\Html\TagFactory;
use PHPUnit\Framework\TestCase;

final class UnderscoreInvokeTest extends TestCase
{
    /**
     * Tests Phalcon\Html\Helper\Form :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperFormUnderscoreInvoke(
        string $expected,
        array $attributes,
    ): void {
        $escaper = new Escaper();
        $helper  = new Form($escaper);

        $actual   = $helper($attributes);
        $this->assertSame($expected, $actual);

        $factory  = new TagFactory($escaper);
        $locator  = $factory->newInstance('form');

        $actual   = $locator($attributes);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public static function providerExamples(): array
    {
        return [
            [
                '<form method="post" enctype="multipart/form-data">',
                [],
            ],
            [
                '<form id="my-id" name="my-name" method="post" enctype="multipart/form-data">',
                [
                    'id'   => 'my-id',
                    'name' => 'my-name',
                ],
            ],
            [
                '<form id="my-id" name="my-name" class="my-class" method="post" enctype="multipart/form-data">',
                [
                    'class' => 'my-class',
                    'name'  => 'my-name',
                    'id'    => 'my-id',
                ],
            ],
        ];
    }
}
