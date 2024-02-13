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

use MyLibrary\Tests\support\data\fixtures\Annotations\Adapter\StreamEmptyDataFixture;
use MyLibrary\Tests\support\data\fixtures\Annotations\Adapter\StreamWrongDataFixture;
use Phalcon\Annotations\Adapter\Stream;
use Phalcon\Annotations\Exception;
use Phalcon\Annotations\Reflection;
use Phalcon\Tests1\Fixtures\Annotations\Adapter\StreamFileExistsFixture;
use Phalcon\Tests1\Fixtures\Annotations\Adapter\StreamFixture;
use Phalcon\Tests1\Fixtures\Traits\AnnotationsTrait2;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use TestClass;

use function dataDir2;
use function outputDir2;
use function safeDeleteFile2;

final class ReadWriteTest extends TestCase
{
    use AnnotationsTrait2;

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
        require_once dataDir2('fixtures/Annotations/TestClass.php');

        /**
         * File does not exist
         */
        $params  = [
            'annotationsDir' => outputDir2('annotations/'),
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
        require_once dataDir2('fixtures/Annotations/TestClass.php');

        $params  = [
            'annotationsDir' => outputDir2('annotations/'),
        ];
        $adapter = new Stream($params);

        $classAnnotations = $adapter->get(TestClass::class);

        $adapter->write('testwrite', $classAnnotations);

        $actual = outputDir2('annotations/testclass.php');
        $this->assertFileExists($actual);

        $newClass = $adapter->read('testwrite');
        $expected = Reflection::class;
        $actual   = $newClass;
        $this->assertInstanceOf($expected, $actual);

        safeDeleteFile2(outputDir2('annotations/testwrite.php'));
        safeDeleteFile2(outputDir2('annotations/testclass.php'));
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
        require_once dataDir2('fixtures/Annotations/TestClass.php');

        $params  = [
            'annotationsDir' => outputDir2('annotations/'),
        ];
        $adapter = new StreamFixture($params);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Annotations directory cannot be written');
        $classAnnotations = $adapter->get(TestClass::class);

        $adapter->write('testwrite', $classAnnotations);

        safeDeleteFile2(outputDir2('annotations/testwrite.php'));
        safeDeleteFile2(outputDir2('annotations/testclass.php'));
    }
}
