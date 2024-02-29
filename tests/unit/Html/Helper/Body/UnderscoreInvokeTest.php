<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Body;

use Codeception\Example;
use Phalcon\Html\Escaper;
use Phalcon\Html\Exception;
use Phalcon\Html\Helper\Body;
use Phalcon\Html\TagFactory;
use PHPUnit\Framework\TestCase;

final class UnderscoreInvokeTest extends TestCase
{
    /**
     * Tests Phalcon\Html\Helper\Body :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlHelperBodyUnderscoreInvoke(
        string $expected,
        array $attributes,
    ): void {
        $escaper = new Escaper();
        $helper  = new Body($escaper);

        $actual   = $helper($attributes);
        $this->assertSame($expected, $actual);

        $factory  = new TagFactory($escaper);
        $locator  = $factory->newInstance('body');

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
                '<body>',
                [],
            ],
            [
                '<body id="my-id">',
                [
                    'id' => 'my-id',
                ],
            ],
            [
                '<body id="my-id" class="my-class">',
                [
                    'class' => 'my-class',
                    'id'    => 'my-id',
                ],
            ],
        ];
    }
}
