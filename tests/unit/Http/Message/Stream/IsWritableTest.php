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

namespace Phalcon\Tests\Unit\Http\Message\Stream;

use Phalcon\Http\Message\Stream;
use Phalcon\Http\Message\Stream\Input;
use Phalcon\Http\Message\Stream\Memory;
use Phalcon\Http\Message\Stream\Temp;
use PHPUnit\Framework\TestCase;

use function dataDir2;
use function getNewFileName2;
use function logsDir2;

final class IsWritableTest extends TestCase
{
    /**
     * @return array[]
     */
    public static function providerExamplesX(): array
    {
        return [
            ['w', true],
            ['wb', true],
            ['w+', true],
            ['w+b', true],
            ['x', true],
            ['xb', true],
            ['x+', true],
            ['x+b', true],
        ];
    }

    /**
     * Tests Phalcon\Http\Message\Stream :: isWritable()
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-02-10
     */
    public function testHttpMessageStreamIsWritable(): void
    {
        $data = $this->providerData();

        foreach ($data as $item) {
            $stream = $item[0];

            $expected = $item[1];
            $actual   = $stream->isWritable();
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Http\Message\Stream :: isWritable() - with "x"
     *
     * @dataProvider providerExamplesX
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2019-02-10
     */
    public function testHttpMessageStreamIsWritableWithX(
        string $mode,
        bool $expected
    ): void {
        $fileName = getNewFileName2();
        $fileName = logsDir2($fileName);

        $stream = new Stream($fileName, $mode);

        $actual = $stream->isWritable();
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    private function providerData(): array
    {
        $fileName = dataDir2('assets/stream/mit-empty.txt');

        return [
            [
                new Stream($fileName, 'a'),
                true,
            ],
            [
                new Stream($fileName, 'ab'),
                true,
            ],
            [
                new Stream($fileName, 'a+'),
                true,
            ],
            [
                new Stream($fileName, 'a+b'),
                true,
            ],
            [
                new Stream($fileName, 'c'),
                true,
            ],
            [
                new Stream($fileName, 'cb'),
                true,
            ],
            [
                new Stream($fileName, 'c+'),
                true,
            ],
            [
                new Stream($fileName, 'c+b'),
                true,
            ],
            [
                new Stream($fileName, 'r'),
                false,
            ],
            [
                new Stream($fileName, 'rb'),
                false,
            ],
            [
                new Stream($fileName, 'r+'),
                true,
            ],
            [
                new Stream($fileName, 'r+b'),
                true,
            ],
            [
                new Input(),
                false,
            ],
            [
                new Memory('a'),
                true,
            ],
            [
                new Memory('ab'),
                true,
            ],
            [
                new Memory('a+'),
                true,
            ],
            [
                new Memory('a+b'),
                true,
            ],
            [
                new Memory('c'),
                false,
            ],
            [
                new Memory('cb'),
                false,
            ],
            [
                new Memory('c+'),
                true,
            ],
            [
                new Memory('c+b'),
                true,
            ],
            [
                new Memory('r'),
                false,
            ],
            [
                new Memory('rb'),
                false,
            ],
            [
                new Memory('r+'),
                true,
            ],
            [
                new Memory('r+b'),
                true,
            ],
            [
                new Temp('a'),
                true,
            ],
            [
                new Temp('ab'),
                true,
            ],
            [
                new Temp('a+'),
                true,
            ],
            [
                new Temp('a+b'),
                true,
            ],
            [
                new Temp('c'),
                false,
            ],
            [
                new Temp('cb'),
                false,
            ],
            [
                new Temp('c+'),
                true,
            ],
            [
                new Temp('c+b'),
                true,
            ],
            [
                new Temp('r'),
                false,
            ],
            [
                new Temp('rb'),
                false,
            ],
            [
                new Temp('r+'),
                true,
            ],
            [
                new Temp('r+b'),
                true,
            ],
        ];
    }
}
