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
use Phalcon\Tests1\Fixtures\Traits\AnnotationsTrait2;
use PHPUnit\Framework\TestCase;
use TestClass;

use function dataDir2;
use function outputDir2;
use function safeDeleteFile2;

final class GetPropertyTest extends TestCase
{
    use AnnotationsTrait2;

    /**
     * Tests Phalcon\Annotations\Adapter :: getProperty()
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
    public function testAnnotationsAdapterGetProperty(
        string $class,
        array $params
    ): void {
        require_once dataDir2('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $propertyAnnotation = $adapter->getProperty(
            TestClass::class,
            'testProp1'
        );

        $expected = Collection::class;
        $actual   = $propertyAnnotation;
        $this->assertInstanceOf($expected, $actual);

        safeDeleteFile2(outputDir2('annotations/') . 'testclass.php');
    }
}
