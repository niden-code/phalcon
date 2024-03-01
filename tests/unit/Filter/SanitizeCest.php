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

namespace Phalcon\Tests\Unit\Filter;

use Phalcon\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

use function call_user_func_array;
use function is_array;

final class SanitizeTest extends TestCase
{
    /**
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'class'  => 'absint',
                'method' => '',
                'source' => -125,
            ],
            [
                'class'    => 'absint',
                'method'   => '',
                'source'   => -125,
                'expected' => 125,
            ],
            [
                'class'    => 'absint',
                'method'   => '',
                'source'   => -125,
                'expected' => 125,
            ],
            [
                'class'    => 'absint',
                'method'   => 'absint',
                'source'   => [-125],
                'expected' => 125,
            ],
            [
                'class'    => 'absint',
                'method'   => 'absint',
                'source'   => [-125],
                'expected' => 125,
            ],
            [
                'class'    => 'absint',
                'method'   => 'absint',
                'source'   => [-125],
                'expected' => 125,
            ],
            [
                'class'    => 'alnum',
                'method'   => '',
                'source'   => '0',
                'expected' => '0',
            ],
            [
                'class'    => 'alnum',
                'method'   => '',
                'source'   => '',
                'expected' => '',
            ],
            [
                'class'    => 'alnum',
                'method'   => '',
                'source'   => '?a&5xka\tŧ?1-s.Xa[\n',
                'expected' => 'a5xkat1sXan',
            ],
            [
                'class'    => 'alnum',
                'method'   => 'alnum',
                'source'   => ['0'],
                'expected' => '0',
            ],
            [
                'class'    => 'alnum',
                'method'   => 'alnum',
                'source'   => [''],
                'expected' => '',
            ],
            [
                'class'    => 'alnum',
                'method'   => 'alnum',
                'source'   => ['?a&5xka\tŧ?1-s.Xa[\n'],
                'expected' => 'a5xkat1sXan',
            ],
            [
                'class'    => 'alpha',
                'method'   => '',
                'source'   => '0',
                'expected' => '',
            ],
            [
                'class'    => 'alpha',
                'method'   => '',
                'source'   => '',
                'expected' => '',
            ],
            [
                'class'    => 'alpha',
                'method'   => '',
                'source'   => '?a&5xka\tŧ?1-s.Xa[\n',
                'expected' => 'axkatsXan',
            ],
            [
                'class'    => 'alpha',
                'method'   => 'alpha',
                'source'   => ['0'],
                'expected' => '',
            ],
            [
                'class'    => 'alpha',
                'method'   => 'alpha',
                'source'   => [''],
                'expected' => '',
            ],
            [
                'class'    => 'alpha',
                'method'   => 'alpha',
                'source'   => ['?a&5xka\tŧ?1-s.Xa[\n'],
                'expected' => 'axkatsXan',
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 1000,
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 0xFFA,
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => '1000',
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => null,
                'expected' => false,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 'on',
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 'off',
                'expected' => false,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 'yes',
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 'no',
                'expected' => false,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 'y',
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => '',
                'source'   => 'n',
                'expected' => false,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => [1000],
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => [0xFFA],
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => ['1000'],
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => [null],
                'expected' => false,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => ['on'],
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => ['off'],
                'expected' => false,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => ['yes'],
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => ['no'],
                'expected' => false,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => ['y'],
                'expected' => true,
            ],
            [
                'class'    => 'bool',
                'method'   => 'bool',
                'source'   => ['n'],
                'expected' => false,
            ],
            [
                'class'    => 'email',
                'method'   => '',
                'source'   => "some(one)@exa\\mple.com",
                'expected' => 'someone@example.com',
            ],
            [
                'class'    => 'email',
                'method'   => '',
                'source'   => "!(first.guy)
                    @*my-domain**##.com.rx//",
                'expected' => "!first.guy@*my-domain**##.com.rx",
            ],
            [
                'class'    => 'email',
                'method'   => 'email',
                'source'   => ["some(one)@exa\\mple.com"],
                'expected' => 'someone@example.com',
            ],
            [
                'class'    => 'email',
                'method'   => 'email',
                'source'   => [
                    "!(first.guy)
                    @*my-domain**##.com.rx//",
                ],
                'expected' => "!first.guy@*my-domain**##.com.rx",
            ],
            [
                'class'    => 'float',
                'method'   => '',
                'source'   => '1000.01',
                'expected' => 1000.01,
            ],
            [
                'class'    => 'float',
                'method'   => '',
                'source'   => 0xFFA,
                'expected' => 0xFFA,
            ],
            [
                'class'    => 'float',
                'method'   => '',
                'source'   => "lol",
                'expected' => 0.0,
            ],
            [
                'class'    => 'float',
                'method'   => 'float',
                'source'   => ['1000.01'],
                'expected' => 1000.01,
            ],
            [
                'class'    => 'float',
                'method'   => 'float',
                'source'   => [0xFFA],
                'expected' => 0xFFA,
            ],
            [
                'class'    => 'float',
                'method'   => 'float',
                'source'   => ["lol"],
                'expected' => 0.0,
            ],
            [
                'class'    => 'int',
                'method'   => '',
                'source'   => '1000',
                'expected' => 1000,
            ],
            [
                'class'    => 'int',
                'method'   => '',
                'source'   => 0xFFA,
                'expected' => 0xFFA,
            ],
            [
                'class'    => 'int',
                'method'   => '',
                'source'   => "lol",
                'expected' => 0,
            ],
            [
                'class'    => 'int',
                'method'   => '',
                'source'   => '!100a019.01a',
                'expected' => 10001901,
            ],
            [
                'class'    => 'int',
                'method'   => 'int',
                'source'   => [1000],
                'expected' => 1000,
            ],
            [
                'class'    => 'int',
                'method'   => 'int',
                'source'   => [0xFFA],
                'expected' => 0xFFA,
            ],
            [
                'class'    => 'int',
                'method'   => 'int',
                'source'   => ["lol"],
                'expected' => 0,
            ],
            [
                'class'    => 'int',
                'method'   => 'int',
                'source'   => ['!100a019.01a'],
                'expected' => 10001901,
            ],
            [
                'class'    => 'lower',
                'method'   => '',
                'source'   => 'test',
                'expected' => 'test',
            ],
            [
                'class'    => 'lower',
                'method'   => '',
                'source'   => 'tEsT',
                'expected' => 'test',
            ],
            [
                'class'    => 'lower',
                'method'   => '',
                'source'   => "TEST",
                'expected' => 'test',
            ],
            [
                'class'    => 'lower',
                'method'   => 'lower',
                'source'   => ['test'],
                'expected' => 'test',
            ],
            [
                'class'    => 'lower',
                'method'   => 'lower',
                'source'   => ['tEsT'],
                'expected' => 'test',
            ],
            [
                'class'    => 'lower',
                'method'   => 'lower',
                'source'   => ["TEST"],
                'expected' => 'test',
            ],
            [
                'class'    => 'lowerfirst',
                'method'   => '',
                'source'   => 'test',
                'expected' => 'test',
            ],
            [
                'class'    => 'lowerfirst',
                'method'   => '',
                'source'   => 'tEsT',
                'expected' => 'tEsT',
            ],
            [
                'class'    => 'lowerfirst',
                'method'   => '',
                'source'   => "TEST",
                'expected' => 'tEST',
            ],
            [
                'class'    => 'lowerfirst',
                'method'   => 'lowerfirst',
                'source'   => ['test'],
                'expected' => 'test',
            ],
            [
                'class'    => 'lowerfirst',
                'method'   => 'lowerfirst',
                'source'   => ['tEsT'],
                'expected' => 'tEsT',
            ],
            [
                'class'    => 'lowerfirst',
                'method'   => 'lowerfirst',
                'source'   => ["TEST"],
                'expected' => 'tEST',
            ],
            [
                'class'    => 'regex',
                'method'   => '',
                'source'   => ['mary abc a little lamb', '/abc/', 'had'],
                'expected' => 'mary had a little lamb',
            ],
            [
                'class'    => 'regex',
                'method'   => 'regex',
                'source'   => ['mary abc a little lamb', '/abc/', 'had'],
                'expected' => 'mary had a little lamb',
            ],
            [
                'class'    => 'remove',
                'method'   => '',
                'source'   => ['mary had a little lamb', 'a'],
                'expected' => 'mry hd  little lmb',
            ],
            [
                'class'    => 'remove',
                'method'   => 'remove',
                'source'   => ['mary had a little lamb', 'a'],
                'expected' => 'mry hd  little lmb',
            ],
            [
                'class'    => 'replace',
                'method'   => '',
                'source'   => ['test test', 'e', 'a'],
                'expected' => 'tast tast',
            ],
            [
                'class'    => 'replace',
                'method'   => '',
                'source'   => ['tEsT tEsT', 'E', 'A'],
                'expected' => 'tAsT tAsT',
            ],
            [
                'class'    => 'replace',
                'method'   => '',
                'source'   => ['TEST TEST', 'E', 'A'],
                'expected' => 'TAST TAST',
            ],
            [
                'class'    => 'replace',
                'method'   => 'replace',
                'source'   => ['test test', 'e', 'a'],
                'expected' => 'tast tast',
            ],
            [
                'class'    => 'replace',
                'method'   => 'replace',
                'source'   => ['tEsT tEsT', 'E', 'A'],
                'expected' => 'tAsT tAsT',
            ],
            [
                'class'    => 'replace',
                'label'    => 'replace()',
                'method'   => 'replace',
                'source'   => ['TEST TEST', 'E', 'A'],
                'expected' => 'TAST TAST',
            ],
            [
                'class'    => 'special',
                'method'   => '',
                'source'   => ['This is <html> tags'],
                'expected' => 'This is &#60;html&#62; tags',
            ],
            [
                'class'    => 'special',
                'method'   => 'special',
                'source'   => ['This is <html> tags'],
                'expected' => 'This is &#60;html&#62; tags',
            ],
            [
                'class'    => 'specialfull',
                'method'   => '',
                'source'   => ['This is <html> tags'],
                'expected' => 'This is &lt;html&gt; tags',
            ],
            [
                'class'    => 'specialfull',
                'method'   => 'specialfull',
                'source'   => ['This is <html> tags'],
                'expected' => 'This is &lt;html&gt; tags',
            ],
            [
                'class'    => 'string',
                'method'   => '',
                'source'   => 'abcdefghijklmnopqrstuvwzyx1234567890!@#$%^&*()_ `~=+<>',
                'expected' => 'abcdefghijklmnopqrstuvwzyx1234567890!@#$%^&amp;*()_ `~=+&lt;&gt;',
            ],
            [
                'class'    => 'string',
                'method'   => '',
                'source'   => '{[<within french quotes>]}',
                'expected' => '{[&lt;within french quotes&gt;]}',
            ],
            [
                'class'    => 'string',
                'method'   => '',
                'source'   => '',
                'expected' => '',
            ],
            [
                'class'    => 'string',
                'method'   => '',
                'source'   => 'buenos días123καλημέρα!@#$%^&*早安()_ `~=+<>',
                'expected' => 'buenos días123καλημέρα!@#$%^&amp;*早安()_ `~=+&lt;&gt;',
            ],
            [
                'class'    => 'string',
                'method'   => '',
                'source'   => '{[<buenos días 123 καλημέρα! 早安>]}',
                'expected' => '{[&lt;buenos días 123 καλημέρα! 早安&gt;]}',
            ],
            [
                'class'    => 'string',
                'method'   => 'string',
                'source'   => ['abcdefghijklmnopqrstuvwzyx1234567890!@#$%^&*()_ `~=+<>'],
                'expected' => 'abcdefghijklmnopqrstuvwzyx1234567890!@#$%^&amp;*()_ `~=+&lt;&gt;',
            ],
            [
                'class'    => 'string',
                'method'   => 'string',
                'source'   => ['{[<within french quotes>]}'],
                'expected' => '{[&lt;within french quotes&gt;]}',
            ],
            [
                'class'    => 'string',
                'method'   => 'string',
                'source'   => [''],
                'expected' => '',
            ],
            [
                'class'    => 'string',
                'method'   => 'string',
                'source'   => ['buenos días123καλημέρα!@#$%^&*早安()_ `~=+<>'],
                'expected' => 'buenos días123καλημέρα!@#$%^&amp;*早安()_ `~=+&lt;&gt;',
            ],
            [
                'class'    => 'string',
                'method'   => 'string',
                'source'   => ['{[<buenos días 123 καλημέρα! 早安>]}'],
                'expected' => '{[&lt;buenos días 123 καλημέρα! 早安&gt;]}',
            ],
            [
                'class'    => 'striptags',
                'method'   => '',
                'source'   => '<h1>Hello</h1>',
                'expected' => 'Hello',
            ],
            [
                'class'    => 'striptags',
                'method'   => '',
                'source'   => '<h1><p>Hello</h1>',
                'expected' => 'Hello',
            ],
            [
                'class'    => 'striptags',
                'method'   => '',
                'source'   => "<",
                'expected' => '',
            ],
            [
                'class'    => 'striptags',
                'method'   => 'striptags',
                'source'   => ['<h1>Hello</h1>'],
                'expected' => 'Hello',
            ],
            [
                'class'    => 'striptags',
                'method'   => 'striptags',
                'source'   => ['<h1><p>Hello</h1>'],
                'expected' => 'Hello',
            ],
            [
                'class'    => 'striptags',
                'method'   => 'striptags',
                'source'   => ["<"],
                'expected' => '',
            ],
            [
                'class'    => 'trim',
                'method'   => '',
                'source'   => '    Hello',
                'expected' => 'Hello',
            ],
            [
                'class'    => 'trim',
                'method'   => '',
                'source'   => 'Hello    ',
                'expected' => 'Hello',
            ],
            [
                'class'    => 'trim',
                'method'   => '',
                'source'   => "  Hello    ",
                'expected' => 'Hello',
            ],
            [
                'class'    => 'trim',
                'method'   => 'trim',
                'source'   => ['    Hello'],
                'expected' => 'Hello',
            ],
            [
                'class'    => 'trim',
                'method'   => 'trim',
                'source'   => ['Hello    '],
                'expected' => 'Hello',
            ],
            [
                'class'    => 'trim',
                'method'   => 'trim',
                'source'   => ["  Hello    "],
                'expected' => 'Hello',
            ],
            [
                'class'    => 'upper',
                'method'   => '',
                'source'   => 'test',
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upper',
                'method'   => '',
                'source'   => 'tEsT',
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upper',
                'method'   => '',
                'source'   => "TEST",
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upper',
                'method'   => 'upper',
                'source'   => ['test'],
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upper',
                'method'   => 'upper',
                'source'   => ['tEsT'],
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upper',
                'method'   => 'upper',
                'source'   => ["TEST"],
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upperfirst',
                'method'   => '',
                'source'   => 'test',
                'expected' => 'Test',
            ],
            [
                'class'    => 'upperfirst',
                'method'   => '',
                'source'   => 'tEsT',
                'expected' => 'TEsT',
            ],
            [
                'class'    => 'upperfirst',
                'method'   => '',
                'source'   => "TEST",
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upperfirst',
                'method'   => 'upperfirst',
                'source'   => ['test'],
                'expected' => 'Test',
            ],
            [
                'class'    => 'upperfirst',
                'method'   => 'upperfirst',
                'source'   => ['tEsT'],
                'expected' => 'TEsT',
            ],
            [
                'class'    => 'upperfirst',
                'method'   => 'upperfirst',
                'source'   => ["TEST"],
                'expected' => 'TEST',
            ],
            [
                'class'    => 'upperwords',
                'method'   => '',
                'source'   => 'mary had a little lamb',
                'expected' => 'Mary Had A Little Lamb',
            ],
            [
                'class'    => 'upperwords',
                'method'   => 'upperwords',
                'source'   => ['mary had a little lamb'],
                'expected' => 'Mary Had A Little Lamb',
            ],
            [
                'class'    => 'url',
                'method'   => '',
                'source'   => 'https://pha�lc�on.i�o',
                'expected' => 'https://phalcon.io',
            ],
            [
                'class'    => 'url',
                'method'   => 'url',
                'source'   => ['https://pha�lc�on.i�o'],
                'expected' => 'https://phalcon.io',
            ],
        ];
    }

    /**
     * Tests Phalcon\Filter\Sanitize\*t :: __invoke()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2021-11-07
     */
    public function testFilterSanitize(
        string $class,
        string $method,
        mixed $source,
    ): void {
        $factory = new FilterFactory();
        $filter  = $factory->newInstance();
        if (true === empty($method)) {
            $sanitizer = $filter->get($class);
            if (is_array($source)) {
                $actual = call_user_func_array([$sanitizer, '__invoke'], $source);
            } else {
                $actual = $sanitizer->__invoke($source);
            }
        } else {
            $actual = call_user_func_array([$filter, $method], $source);
        }

        $expected = is_array($source) ? $source[0] : $source;
        $this->assertSame($expected, $actual);
    }
}
