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

final class GetPropertiesTest extends AbstractAnnotationsAdapterTestCase
{
    /**
     * Tests Phalcon\Annotations\Adapter :: getProperties()
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
    public function testAnnotationsAdapterGetProperties(
        string $class,
        array $params
    ): void {
        require_once self::dataDir('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $propertyAnnotations = $adapter->getProperties(TestClass::class);

        $keys     = array_keys($propertyAnnotations);
        $expected = [
            'testProp1',
            'testProp3',
            'testProp4',
        ];
        $actual   = $keys;
        $this->assertSame($expected, $actual);

        foreach ($propertyAnnotations as $propertyAnnotation) {
            $expected = Collection::class;
            $actual   = $propertyAnnotation;
            $this->assertInstanceOf($expected, $actual);
        }

        $this->safeDeleteFile(self::outputDir('annotations/testclass.php'));
    }
}
