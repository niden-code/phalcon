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

use Phalcon\Annotations\Adapter\AdapterInterface;
use Phalcon\Annotations\Collection;
use Phalcon\Annotations\Reflection;
use TestClass;
use User\TestClassNs;

use function is_object;

final class ConstructTest extends AbstractAnnotationsAdapterTestCase
{
    /**
     * Tests Phalcon\Annotations\Adapter ::
     *
     * @dataProvider providerExamples
     *
     * @param string $class
     * @param array  $params
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2022-12-30
     */
    public function testAnnotationsAdapter(
        string $class,
        array $params
    ): void {
        $this->assertFileExists(
            self::dataDir('fixtures/Annotations/TestClass.php')
        );

        $this->assertFileExists(
            self::dataDir('fixtures/Annotations/TestClassNs.php')
        );

        require_once self::dataDir('fixtures/Annotations/TestClass.php');
        require_once self::dataDir('fixtures/Annotations/TestClassNs.php');

        $adapter = new $class($params);

        $classAnnotations = $adapter->get(TestClass::class);

        $actual = is_object($classAnnotations);
        $this->assertTrue($actual);

        $expected = Reflection::class;
        $actual   = $classAnnotations;
        $this->assertInstanceOf($expected, $actual);

        $expected = Collection::class;
        $actual   = $classAnnotations->getClassAnnotations();
        $this->assertInstanceOf($expected, $actual);

        $classAnnotations = $adapter->get(TestClassNs::class);

        $actual = is_object($classAnnotations);
        $this->assertTrue($actual);

        $expected = Reflection::class;
        $actual   = $classAnnotations;
        $this->assertInstanceOf($expected, $actual);

        $expected = Collection::class;
        $actual   = $classAnnotations->getClassAnnotations();
        $this->assertInstanceOf($expected, $actual);


        $property = $adapter->getProperty(
            TestClass::class,
            'testProp1'
        );

        $actual = is_object($property);
        $this->assertTrue($actual);

        $expected = Collection::class;
        $actual   = $property;
        $this->assertInstanceOf($expected, $actual);

        $expected = 4;
        $actual   = $property->count();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Annotations\Adapter :: __construct()
     *
     * @dataProvider providerExamples
     *
     * @param string $class
     * @param array  $params
     *
     * @return void
     *
     * @author       Phalcon Team <team@phalcon.io>
     * @since        2022-12-30
     */
    public function testAnnotationsAdapterConstruct(
        string $class,
        array $params
    ): void {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $expected = AdapterInterface::class;
        $actual   = $adapter;
        $this->assertInstanceOf($expected, $actual);
    }
}
