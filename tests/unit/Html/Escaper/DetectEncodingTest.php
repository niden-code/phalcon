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

namespace Phalcon\Tests\Unit\Html\Escaper;

use Codeception\Example;
use Phalcon\Html\Escaper;
use PHPUnit\Framework\TestCase;

final class DetectEncodingTest extends TestCase
{
    /**
     * Tests Phalcon\Escaper :: detectEncoding()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testHtmlEscaperDetectEncoding(
        string $source,
        string $expected
    ): void {
        $escaper = new Escaper();

        $actual   = $escaper->detectEncoding($source);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return string[][]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'ḂḃĊċḊḋḞḟĠġṀṁ',
                'UTF-8',
            ],

            [
                chr(172) . chr(128) . chr(159) . 'ḂḃĊċḊḋḞḟĠġṀṁ',
                'ISO-8859-1',
            ],

            [
                '\0\0\0H\0\0\0i',
                'UTF-8',
            ],
        ];
    }
}
