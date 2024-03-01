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

namespace Phalcon\Tests\Unit\Translate;

use Exception as BaseException;
use Phalcon\Support\Exception as SupportException;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Traits\FactoryTrait2;
use Phalcon\Translate\Adapter\AdapterInterface;
use Phalcon\Translate\Adapter\Csv;
use Phalcon\Translate\Adapter\Gettext;
use Phalcon\Translate\Exception;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

use function strtolower;
use function uniqid;

use const PHP_OS;

#[RequiresPhpExtension('gettext')]
final class TranslateFactoryTest extends AbstractUnitTestCase
{
    use FactoryTrait2;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->init();
    }

    /**
     * Tests Phalcon\Translate\Factory :: load() - array
     *
     * @return void
     * @throws SupportException
     *
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2017-03-02
     */
    public function testTranslateFactoryLoadArray(): void
    {
        /**
         * This test will run only on Linux - unless we figure out how to
         * properly set locales on windows/macos
         */
        if ('linux' === strtolower(PHP_OS)) {
            $options      = $this->arrayConfig['translate'];
            $interpolator = new InterpolatorFactory();
            $factory      = new TranslateFactory($interpolator);
            $adapter      = $factory->load($options);
            $locale       = $options['options']['locale'][0];

            $this->assertInstanceOf(Gettext::class, $adapter);

            $expected = $options['options']['category'];
            $actual   = $adapter->getCategory();
            $this->assertSame($expected, $actual);

            $expected = $locale;
            $actual   = $adapter->getLocale();
            $this->assertSame($expected, $actual);

            $expected = $options['options']['defaultDomain'];
            $actual   = $adapter->getDefaultDomain();
            $this->assertSame($expected, $actual);

            $expected = $options['options']['directory'];
            $actual   = $adapter->getDirectory();
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\Factory :: load() - Phalcon\Config
     *
     * @return void
     * @throws SupportException
     *
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2017-03-02
     */
    public function testTranslateFactoryLoadConfig(): void
    {
        /**
         * This test will run only on Linux - unless we figure out how to
         * properly set locales on windows/macos
         */
        if ('linux' === strtolower(PHP_OS)) {
            $options      = $this->config->translate;
            $interpolator = new InterpolatorFactory();
            $factory      = new TranslateFactory($interpolator);
            $adapter      = $factory->load($options);
            $locale       = $options->options->locale[0];

            $this->assertInstanceOf(Gettext::class, $adapter);

            $expected = $options->options->category;
            $actual   = $adapter->getCategory();
            $this->assertSame($expected, $actual);

            $expected = $locale;
            $actual   = $adapter->getLocale();
            $this->assertSame($expected, $actual);

            $expected = $options->options->defaultDomain;
            $actual   = $adapter->getDefaultDomain();
            $this->assertSame($expected, $actual);

            $expected = $options->options->directory;
            $actual   = $adapter->getDirectory();
            $this->assertSame($expected, $actual);
        }
    }

    /**
     * Tests Phalcon\Translate\TranslateFactory :: newInstance()
     *
     * @return void
     * @throws BaseException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateTranslateFactoryNewInstance(): void
    {
        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);
        $language     = $this->getCsvConfig()['ru'];
        $adapter      = $factory->newInstance('csv', $language);

        $this->assertInstanceOf(Csv::class, $adapter);
        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    /**
     * Tests Phalcon\Translate\TranslateFactory :: newInstance() - exception
     *
     * @return void
     * @throws BaseException
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testTranslateTranslateFactoryNewInstanceException(): void
    {
        $name = uniqid('service-');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service ' . $name . ' is not registered');

        $interpolator = new InterpolatorFactory();
        $factory      = new TranslateFactory($interpolator);
        $factory->newInstance($name);
    }

    /**
     * @return array[]
     */
    protected function getCsvConfig(): array
    {
        return [
            'en' => [
                'content' => self::dataDir('assets/translation/csv/en.csv'),
            ],
            'es' => [
                'content' => self::dataDir('assets/translation/csv/es_ES.csv'),
            ],
            'fr' => [
                'content'   => self::dataDir('assets/translation/csv/fr_FR.csv'),
                'delimiter' => '|',
                'enclosure' => "'",
            ],
            'ru' => [
                'content' => self::dataDir('assets/translation/csv/ru_RU.csv'),
            ],
        ];
    }
}
