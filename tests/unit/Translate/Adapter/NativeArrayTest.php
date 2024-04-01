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

namespace Phalcon\Tests\Unit\Translate\Adapter;

use ArrayAccess;
use Phalcon\Tests\Fixtures\Translate\Adapter\NativeAdapter;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Translate\Adapter\AdapterInterface;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\Exception;
use Phalcon\Translate\InterpolatorFactory;

final class NativeArrayTest extends AbstractUnitTestCase
{
    /**
     * Data provider for the query tests
     *
     * @return array[]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'method' => 'query',
                'code'   => 'en',
                'tests'  => [
                    'hi'  => 'Hello',
                    'bye' => 'Good Bye',
                ],
            ],
            [
                'method' => 'query',
                'code'   => 'es',
                'tests'  => [
                    'hi'  => 'Hola',
                    'bye' => 'Adiós',
                ],
            ],
            [
                'method' => 'query',
                'code'   => 'fr',
                'tests'  => [
                    'hi'  => 'Bonjour',
                    'bye' => 'Au revoir',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'en',
                'tests'  => [
                    'hi'  => 'Hello',
                    'bye' => 'Good Bye',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'es',
                'tests'  => [
                    'hi'  => 'Hola',
                    'bye' => 'Adiós',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'fr',
                'tests'  => [
                    'hi'  => 'Bonjour',
                    'bye' => 'Au revoir',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'en',
                'tests'  => [
                    'hi'  => 'Hello',
                    'bye' => 'Good Bye',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'es',
                'tests'  => [
                    'hi'  => 'Hola',
                    'bye' => 'Adiós',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'fr',
                'tests'  => [
                    'hi'  => 'Bonjour',
                    'bye' => 'Au revoir',
                ],
            ],
        ];
    }

    /**
     * Data provider for the query one variable substitution
     *
     * @return array[]
     */
    public static function providerOneVariable(): array
    {
        return [
            [
                'method' => 'query',
                'code'   => 'en',
                'tests'  => [
                    'hello-key' => 'Hello my friend',
                ],
            ],
            [
                'method' => 'query',
                'code'   => 'es',
                'tests'  => [
                    'hello-key' => 'Hola my friend',
                ],
            ],
            [
                'method' => 'query',
                'code'   => 'fr',
                'tests'  => [
                    'hello-key' => 'Bonjour my friend',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'en',
                'tests'  => [
                    'hello-key' => 'Hello my friend',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'es',
                'tests'  => [
                    'hello-key' => 'Hola my friend',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'fr',
                'tests'  => [
                    'hello-key' => 'Bonjour my friend',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'en',
                'tests'  => [
                    'hello-key' => 'Hello my friend',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'es',
                'tests'  => [
                    'hello-key' => 'Hola my friend',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'fr',
                'tests'  => [
                    'hello-key' => 'Bonjour my friend',
                ],
            ],
        ];
    }

    /**
     * Data provider for the query one variable substitution
     *
     * @return array[]
     */
    public static function providerTwoVariables(): array
    {
        return [
            [
                'method' => 'query',
                'code'   => 'en',
                'tests'  => [
                    'song-key' => 'This song is Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => 'query',
                'code'   => 'es',
                'tests'  => [
                    'song-key' => 'La canción es Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => 'query',
                'code'   => 'fr',
                'tests'  => [
                    'song-key' => 'La chanson est Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'en',
                'tests'  => [
                    'song-key' => 'This song is Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'es',
                'tests'  => [
                    'song-key' => 'La canción es Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'fr',
                'tests'  => [
                    'song-key' => 'La chanson est Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'en',
                'tests'  => [
                    'song-key' => 'This song is Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'es',
                'tests'  => [
                    'song-key' => 'La canción es Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'fr',
                'tests'  => [
                    'song-key' => 'La chanson est Dust in the wind (Kansas)',
                ],
            ],
        ];
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: query() - array access
     * and UTF8 strings
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterNativeArrayArrayAccessAndUTF8Strings(
        string $method,
        string $code,
        array $tests
    ): void {
        $language = $this->getConfig()['ru'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $vars     = [
            'fname' => 'John',
            'lname' => 'Doe',
            'mname' => 'D.',
        ];
        $expected = 'Привет, John D. Doe!';
        $actual   = $translator->$method('Hello %fname% %mname% %lname%!', $vars);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayConstruct(): void
    {
        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $this->assertInstanceOf(
            ArrayAccess::class,
            $translator
        );

        $this->assertInstanceOf(
            AdapterInterface::class,
            $translator
        );
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: __construct() - Exception
     * content not array
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayContentNotArray(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Translation data must be an array');

        $actual = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => 1234,
            ]
        );
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: __construct() - Exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayContentParamExist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Translation content was not provided');

        $actual = new NativeArray(new InterpolatorFactory(), []);
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: has()/offsetExists
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayHasOffsetExists(): void
    {
        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $expected = $translator->has('hi');
        $this->assertTrue($expected);

        $expected = $translator->offsetExists('hi');
        $this->assertTrue($expected);
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: notFound() - default
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayNotFound(): void
    {
        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $expected = 'unknown';
        $actual   = $translator->query($expected);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: notFound() - custom
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayNotFoundCustom(): void
    {
        $language = $this->getConfig()['en'];

        $translator = new NativeAdapter(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $expected = '';
        $actual   = $translator->query('unknown');
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: notFound() - triggerError
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayNotFoundTriggerError(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot find translation key: unknown');

        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content'      => $language,
                'triggerError' => true,
            ]
        );

        $translator->query('unknown');
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: notFound() - triggerError
     * random value
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayNotFoundTriggerErrorRandomValue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot find translation key: unknown');

        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content'      => $language,
                'triggerError' => 'blahblah',
            ]
        );

        $translator->query('unknown');
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: offsetGet()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayOffsetGet(): void
    {
        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $expected = 'Hello';
        $actual   = $translator->offsetGet('hi');
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: offsetSet()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayOffsetSet(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Translate is an immutable ArrayAccess object');

        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $translator->offsetSet('team', 'Team');
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: offsetUnset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayOffsetUnset(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Translate is an immutable ArrayAccess object');

        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $translator->offsetUnset('hi');
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: query()
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterNativeArrayQuery(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig()[$code];
        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key);

            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: query() -
     * variable substitution in string with no variables
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterNativeArrayVariableSubstitutionNoVariables(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig()[$code];
        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method(
                $key,
                [
                    'name' => 'my friend',
                ]
            );

            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: query() -
     * variable substitution in string (one variable)
     *
     * @dataProvider providerOneVariable
     * @return void
     *
     * @throws Exception
     */
    public function testTranslateAdapterNativeArrayVariableSubstitutionOneVariable(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig()[$code];
        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key, ['name' => 'my friend']);
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: query() -
     * variable substitution in string (two variables)
     *
     * @dataProvider providerTwoVariables
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterNativeArrayVariableSubstitutionTwoVariable(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig()[$code];
        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $vars = [
            'song'   => 'Dust in the wind',
            'artist' => 'Kansas',
        ];

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key, $vars);

            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: array access
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeArrayWithArrayAccess(): void
    {
        $language = $this->getConfig()['ru'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $actual = isset($translator['Hello!']);
        $this->assertTrue($actual);

        $actual = isset($translator['Hi there!']);
        $this->assertFalse($actual);

        $expected = $language['Hello!'];
        $actual   = $translator['Hello!'];
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\NativeArray :: toArray()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterNativeToArray(): void
    {
        $language = $this->getConfig()['en'];

        $translator = new NativeArray(
            new InterpolatorFactory(),
            [
                'content' => $language,
            ]
        );

        $expected = $language;
        $actual   = $translator->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array[]
     */
    protected function getConfig(): array
    {
        return [
            'en' => [
                'hi'        => 'Hello',
                'bye'       => 'Good Bye',
                'hello-key' => 'Hello %name%',
                'song-key'  => 'This song is %song% (%artist%)',
            ],
            'es' => [
                'hi'        => 'Hola',
                'bye'       => 'Adiós',
                'hello-key' => 'Hola %name%',
                'song-key'  => 'La canción es %song% (%artist%)',
            ],
            'fr' => [
                'hi'        => 'Bonjour',
                'bye'       => 'Au revoir',
                'hello-key' => 'Bonjour %name%',
                'song-key'  => 'La chanson est %song% (%artist%)',
            ],
            'ru' => [
                'Hello!'                         => 'Привет!',
                'Hello %fname% %mname% %lname%!' => 'Привет, %fname% %mname% %lname%!',
            ],
        ];
    }
}
