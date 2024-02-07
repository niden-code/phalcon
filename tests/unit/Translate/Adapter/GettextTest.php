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
use Phalcon\Tests1\Fixtures\Translate\Adapter\GettextFixture;
use Phalcon\Translate\Adapter\AdapterInterface;
use Phalcon\Translate\Adapter\Gettext;
use Phalcon\Translate\Exception;
use Phalcon\Translate\InterpolatorFactory;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;

use function dataDir;
use function setlocale;

use const LC_ALL;
use const LC_MESSAGES;

#[RequiresPhpExtension('gettext')]
final class GettextTest extends TestCase
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
                    'bye' => 'Bye',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'en',
                'tests'  => [
                    'hi'  => 'Hello',
                    'bye' => 'Bye',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'en',
                'tests'  => [
                    'hi'  => 'Hello',
                    'bye' => 'Bye',
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
                'method' => '_',
                'code'   => 'en',
                'tests'  => [
                    'hello-key' => 'Hello my friend',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'en',
                'tests'  => [
                    'hello-key' => 'Hello my friend',
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
                    'song-key' => 'The song is Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => '_',
                'code'   => 'en',
                'tests'  => [
                    'song-key' => 'The song is Dust in the wind (Kansas)',
                ],
            ],
            [
                'method' => 't',
                'code'   => 'en',
                'tests'  => [
                    'song-key' => 'The song is Dust in the wind (Kansas)',
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (!setlocale(LC_ALL, 'en_US.utf8')) {
            $this->markTestSkipped('Locale en_US.utf8 not enabled');
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: query() - array access and
     * UTF8 strings
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterGettextArrayAccessAndUTF8Strings(
        string $method,
        string $code,
        array $tests
    ): void {
        $language = $this->getConfig();

        $translator = new Gettext(new InterpolatorFactory(), $language);

        $vars = [
            'fname' => 'John',
            'lname' => 'Doe',
            'mname' => 'D.',
        ];

        $expected = 'Привет, John D. Doe!';
        $actual   = $translator->$method('Привет, %fname% %mname% %lname%!', $vars);
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextConstruct(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $this->assertInstanceOf(ArrayAccess::class, $translator);
        $this->assertInstanceOf(AdapterInterface::class, $translator);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: __construct() - Exception
     * no gettext extension loaded
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextConstructNoGettextException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('This class requires the gettext extension for PHP');

        $actual = new GettextFixture(
            new InterpolatorFactory(),
            [
                'locale' => 'en_US.utf8',
            ]
        );
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: __construct() - Exception
     * 'directory' not passed in options
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextContentParamDirectoryExist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Parameter 'directory' is required");

        $actual = new Gettext(
            new InterpolatorFactory(),
            [
                'locale' => 'en_US.utf8',
            ]
        );
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: __construct() - Exception
     * 'locale' not passed in options
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextContentParamLocaleExist(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Parameter 'locale' is required");

        $actual = new Gettext(new InterpolatorFactory(), []);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: getCategory()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextGetCategory(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $expected = LC_MESSAGES;
        $actual   = $translator->getCategory();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext ::
     * getDefaultDomain()/setDefaultDomain()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextGetSetDefaultDomain(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $expected = 'messages';
        $actual   = $translator->getDefaultDomain();
        $this->assertSame($expected, $actual);

        $translator->setDefaultDomain('options');

        $expected = 'options';
        $actual   = $translator->getDefaultDomain();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: getDirectory()/setDirectory()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextGetSetDirectory(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $expected = dataDir('assets/translation/gettext');
        $actual   = $translator->getDirectory();
        $this->assertSame($expected, $actual);

        $translator->setDirectory(dataDir());

        $expected = dataDir();
        $actual   = $translator->getDirectory();
        $this->assertSame($expected, $actual);

        $directories = [
            'en_US.utf8' => dataDir(),
            'es_ES.utf8' => dataDir(),
            'fr_FR.utf8' => dataDir(),
        ];
        $translator->setDirectory($directories);

        $expected = $directories;
        $actual   = $translator->getDirectory();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: getLocale()/setLocale()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextGetSetLocale(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $expected = 'en_US.utf8';
        $actual   = $translator->getLocale();
        $this->assertSame($expected, $actual);

        $translator->setLocale(1, ['ru']);

        $actual = $translator->getLocale();
        $this->assertFalse($actual);

        $translator->setLocale(1, ['ru_RU.utf8']);

        $expected = 'ru_RU.utf8';
        $actual   = $translator->getLocale();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: has()/offsetExists()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextHasOffsetExists(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $actual = $translator->has('hi');
        $this->assertTrue($actual);

        $actual = $translator->offsetExists('hi');
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: nquery()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextNquery(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $expected = 'two files';
        $actual   = $translator->nquery('file', 'files', 2);
        $this->assertSame($expected, $actual);

        $expected = 'files';
        $actual   = $translator->nquery(
            'file',
            'files',
            2,
            [],
            'es_ES.utf8'
        );
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: offsetGet()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextOffsetGet(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        $expected = 'Hello';
        $actual   = $translator->offsetGet('hi');
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: offsetSet()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextOffsetSet(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Translate is an immutable ArrayAccess object'
        );

        $language = $this->getConfig();

        $translator = new Gettext(new InterpolatorFactory(), $language);
        $translator->offsetSet('team', 'Team');
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: offsetUnset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextOffsetUnset(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Translate is an immutable ArrayAccess object'
        );

        $language = $this->getConfig();

        $translator = new Gettext(new InterpolatorFactory(), $language);
        $translator->offsetUnset('hi');
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: query()
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterGettextQuery(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $language);

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key);
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: resetDomain()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateAdapterGettextResetDomain(): void
    {
        $params     = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $params);

        // Put the good one to get the return textdomain
        $domainMessage = $translator->setDomain('messages');

        $expected = 'Hello';
        $actual   = $translator->_('hi');
        $this->assertSame($expected, $actual);

        // Check with a domain which doesn't exist
        $domainNotExists = $translator->setDomain('no_exist');
        $expected        = 'hi';
        $actual          = $translator->_('hi');
        $this->assertSame($expected, $actual);

        $domainReset = $translator->resetDomain();
        $expected    = 'Hello';
        $actual      = $translator->_('hi');
        $this->assertSame($expected, $actual);

        $this->assertNotSame($domainNotExists, $domainReset);
        $this->assertSame($domainMessage, $domainReset);
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: query() -
     * variable substitution in string with no variables
     *
     * @dataProvider providerExamples
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterGettextVariableSubstitutionNoVariables(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $language);

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
     * Tests Phalcon\Translate\Adapter\Gettext :: query() -
     * variable substitution in string (one variable)
     *
     * @dataProvider providerOneVariable
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterGettextVariableSubstitutionOneVariable(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $language);

        foreach ($tests as $key => $expected) {
            $actual = $translator->$method($key, ['name' => 'my friend']);
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Adapter\Gettext :: query() -
     * variable substitution in string (two variables)
     *
     * @dataProvider providerTwoVariables
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2020-09-09
     */
    public function testTranslateAdapterGettextVariableSubstitutionTwoVariable(
        string $method,
        string $code,
        array $tests
    ): void {
        $language   = $this->getConfig();
        $translator = new Gettext(new InterpolatorFactory(), $language);

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
     * @return array
     */
    protected function getConfig(): array
    {
        return [
            'locale'        => ['en_US.utf8'],
            'defaultDomain' => 'messages',
            'directory'     => dataDir('assets/translation/gettext'),
            'category'      => LC_MESSAGES,
        ];
    }
}
