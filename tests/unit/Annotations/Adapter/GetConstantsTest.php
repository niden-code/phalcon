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

final class GetConstantsTest extends AbstractAnnotationsAdapterTestCase
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
    public function testAnnotationsAdapterGetConstant(
        string $class,
        array $params
    ): void {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $constantAnnotation = $adapter->getConstant(
            TestClass::class,
            'TEST_CONST1'
        );

        $expected = Collection::class;
        $actual   = $constantAnnotation;
        $this->assertInstanceOf($expected, $actual);

        $this->safeDeleteFile(self::outputDir('annotations/testclass.php'));
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
    public function testAnnotationsAdapterGetConstants(
        string $class,
        array $params
    ): void {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $constantAnnotations = $adapter->getConstants(
            TestClass::class
        );

        $expected = [
            'TEST_CONST1',
        ];
        $actual   = array_keys($constantAnnotations);
        $this->assertSame($expected, $actual);

        $this->safeDeleteFile(self::outputDir('annotations/testclass.php'));
    }
}
