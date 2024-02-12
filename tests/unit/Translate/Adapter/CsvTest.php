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
use Phalcon\Tests1\Fixtures\Translate\Adapter\CsvFixture;
use Phalcon\Translate\Adapter\AdapterInterface;
use Phalcon\Translate\Adapter\Csv;
use Phalcon\Translate\Exception;
use Phalcon\Translate\InterpolatorFactory;
use PHPUnit\Framework\TestCase;

final class CsvTest extends TestCase
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
                'method' => '_',
                'code'   => 'fr',
                'tests'  => [
                    'hello-key' => 'Bonjour my friend',
                ],
            ],
            [
                'method' => 'query',
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
     * Tests Phalcon\Translate\Adapter\Csv :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvConstruct(): void
    {
        $language   = $this->getCsvConfig()['en'];
        $translator = new Csv(new InterpolatorFactory(), $language);

        $this->assertInstanceOf(ArrayAccess::class, $translator);
        $this->assertInstanceOf(AdapterInterface::class, $translator);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: __construct() - Exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvContentParamExist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Parameter 'content' is required");

        $object = new Csv(new InterpolatorFactory(), []);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: __construct() - Exception error
     * loading file
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvErrorLoadingFile(): void
    {
        $message  = "Error opening translation file '"
            . dataDir('assets/translation/csv/en.csv') . "'";
        $language = $this->getCsvConfig()['en'];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($message);

        $translate = new CsvFixture(
            new InterpolatorFactory(),
            $language,
        );

        $actual = $translate->query('test');
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: query()
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterCsvFunction(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getCsvConfig()[$code];
        $translator = new Csv(new InterpolatorFactory(), $language);

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key);

            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: has()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvHasOffsetExists(): void
    {
        $language   = $this->getCsvConfig()['en'];
        $translator = new Csv(new InterpolatorFactory(), $language);

        $actual = $translator->has('hi');
        $this->assertTrue($actual);

        $actual = $translator->offsetExists('hi');
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: offsetGet()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvOffsetGet(): void
    {
        $language   = $this->getCsvConfig()['en'];
        $translator = new Csv(new InterpolatorFactory(), $language);

        $expected = 'Hello';
        $actual   = $translator->offsetGet('hi');
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: offsetSet()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvOffsetSet(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Translate is an immutable ArrayAccess object');

        $language   = $this->getCsvConfig()['en'];
        $translator = new Csv(new InterpolatorFactory(), $language);
        $translator->offsetSet('team', 'Team');
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: offsetUnset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvOffsetUnset(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Translate is an immutable ArrayAccess object');

        $language   = $this->getCsvConfig()['en'];
        $translator = new Csv(new InterpolatorFactory(), $language);
        $translator->offsetUnset('hi');
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: toArray()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvToArray(): void
    {
        $language   = $this->getCsvConfig()['en'];
        $translator = new Csv(new InterpolatorFactory(), $language);

        $expected = [
            'hi'        => 'Hello',
            'bye'       => 'Good Bye',
            'hello-key' => 'Hello %name%',
            'song-key'  => 'This song is %song% (%artist%)',
        ];
        $actual   = $translator->toArray();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: query() -
     * variable substitution in string with no variables
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterCsvVariableSubstitutionNoVariables(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getCsvConfig()[$code];
        $translator = new Csv(new InterpolatorFactory(), $language);

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method(
                $key,
                [
                    'name' => 'my friend',
                ]
            );

            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: query() -
     * variable substitution in string (one variable)
     *
     * @dataProvider providerOneVariable
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterCsvVariableSubstitutionOneVariable(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getCsvConfig()[$code];
        $translator = new Csv(new InterpolatorFactory(), $language);

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key, ['name' => 'my friend']);
            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: query() -
     * variable substitution in string (two variables)
     *
     * @dataProvider providerTwoVariables
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterCsvVariableSubstitutionTwoVariable(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getCsvConfig()[$code];
        $translator = new Csv(new InterpolatorFactory(), $language);
        $vars       = [
            'song'   => 'Dust in the wind',
            'artist' => 'Kansas',
        ];

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key, $vars);

            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: array access
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterCsvWithArrayAccess(): void
    {
        $language = $this->getCsvConfig()['ru'];

        $translator = new Csv(new InterpolatorFactory(), $language);

        $actual = isset($translator['Hello!']);
        $this->assertTrue($actual);

        $actual = isset($translator['Hi there!']);
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Csv :: query() - array access and UTF8
     * strings
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testWithArrayAccessAndUTF8Strings(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getCsvConfig()['ru'];
        $translator = new Csv(new InterpolatorFactory(), $language);

        $vars     = [
            'fname' => 'John',
            'lname' => 'Doe',
            'mname' => 'D.',
        ];
        $expected = 'Привет, John D. Doe!';
        $actual   = $translator->$method('Hello %fname% %mname% %lname%!', $vars);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array[]
     */
    private function getCsvConfig(): array
    {
        return [
            'en' => [
                'content' => dataDir('assets/translation/csv/en.csv'),
            ],
            'es' => [
                'content' => dataDir('assets/translation/csv/es_ES.csv'),
            ],
            'fr' => [
                'content'   => dataDir('assets/translation/csv/fr_FR.csv'),
                'delimiter' => '|',
                'enclosure' => "'",
            ],
            'ru' => [
                'content' => dataDir('assets/translation/csv/ru_RU.csv'),
            ],
        ];
    }
}
