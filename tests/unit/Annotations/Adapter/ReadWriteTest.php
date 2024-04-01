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

namespace Phalcon\Tests\Unit\Annotations\Adapter;

use Phalcon\Annotations\Adapter\Stream;
use Phalcon\Annotations\Exception;
use Phalcon\Annotations\Reflection;
use Phalcon\Tests1\Fixtures\Annotations\Adapter\StreamEmptyDataFixture;
use Phalcon\Tests1\Fixtures\Annotations\Adapter\StreamFileExistsFixture;
use Phalcon\Tests1\Fixtures\Annotations\Adapter\StreamFixture;
use TestClass;

use function dataDir2;
use function outputDir2;
use function safeDeleteFile2;

final class ReadWriteTest extends AbstractAnnotationsAdapterTestCase
{
    /**
     * Tests Phalcon\Annotations\Adapter :: read()/write() - exceptions
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2022-12-30
     */
    public function testAnnotationsAdapterReadException(): void
    {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        /**
         * File does not exist
         */
        $params  = [
            'annotationsDir' => self::outputDir('annotations/'),
        ];
        $adapter = new StreamFileExistsFixture($params);

        $actual = $adapter->read('testwrite');
        $this->assertFalse($actual);

        /**
         * Empty file
         */
        $adapter = new StreamEmptyDataFixture($params);

        $actual = $adapter->read('testwrite');
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Annotations\Adapter :: read()/write()
     *
     * @dataProvider providerExamples
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2022-12-30
     */
    public function testAnnotationsAdapterReadWrite(): void
    {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $params  = [
            'annotationsDir' => self::outputDir('annotations/'),
        ];
        $adapter = new Stream($params);

        $classAnnotations = $adapter->get(TestClass::class);

        $adapter->write('testwrite', $classAnnotations);

        $actual = self::outputDir('annotations/testclass.php');
        $this->assertFileExists($actual);

        $newClass = $adapter->read('testwrite');
        $expected = Reflection::class;
        $actual   = $newClass;
        $this->assertInstanceOf($expected, $actual);

        self::safeDeleteFile(self::outputDir('annotations/testwrite.php'));
        self::safeDeleteFile(self::outputDir('annotations/testclass.php'));
    }

    /**
     * Tests Phalcon\Annotations\Adapter :: write() - stream exception
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2022-12-30
     */
    public function testAnnotationsAdapterWriteStreamException(): void
    {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $params  = [
            'annotationsDir' => self::outputDir('annotations/'),
        ];
        $adapter = new StreamFixture($params);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Annotations directory cannot be written');
        $classAnnotations = $adapter->get(TestClass::class);

        $adapter->write('testwrite', $classAnnotations);

        self::safeDeleteFile(self::outputDir('annotations/testwrite.php'));
        self::safeDeleteFile(self::outputDir('annotations/testclass.php'));
    }
}
