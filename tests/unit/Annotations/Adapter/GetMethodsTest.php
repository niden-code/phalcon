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

use Phalcon\Annotations\Collection;
use TestClass;

use function array_keys;

final class GetMethodsTest extends AbstractAnnotationsAdapterTestCase
{
    /**
     * Tests Phalcon\Annotations\Adapter :: getMethod()
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
    public function testAnnotationsAdapterGetMethod(
        string $class,
        array $params
    ): void {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $methodAnnotation = $adapter->getMethod(
            TestClass::class,
            'testMethod1'
        );

        $expected = Collection::class;
        $actual   = $methodAnnotation;
        $this->assertInstanceOf($expected, $actual);

        $methodAnnotation = $adapter->getMethod(
            TestClass::class,
            'testMethodUnknown'
        );

        $expected = Collection::class;
        $actual   = $methodAnnotation;
        $this->assertInstanceOf($expected, $actual);

        self::safeDeleteFile(self::outputDir('annotations/testclass.php'));
    }

    /**
     * Tests Phalcon\Annotations\Adapter :: getMethods()
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
    public function testAnnotationsAdapterGetMethods(
        string $class,
        array $params
    ): void {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $methodAnnotations = $adapter->getMethods(
            TestClass::class
        );

        $keys     = array_keys($methodAnnotations);
        $expected = [
            'testMethod1',
            'testMethod3',
            'testMethod4',
            'testMethod5',
        ];
        $actual   = $keys;
        $this->assertSame($expected, $actual);

        foreach ($methodAnnotations as $methodAnnotation) {
            $expected = Collection::class;
            $actual   = $methodAnnotation;
            $this->assertInstanceOf($expected, $actual);
        }

        self::safeDeleteFile(self::outputDir('annotations/testclass.php'));
    }
}
