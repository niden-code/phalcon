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

use Phalcon\Annotations\Reader;
use Phalcon\Tests1\Fixtures\Traits\AnnotationsTrait2;
use PHPUnit\Framework\TestCase;

use function dataDir2;

final class GetSetReaderTest extends TestCase
{
    use AnnotationsTrait2;

    /**
     * Tests Phalcon\Annotations\Adapter :: getReader()/setReader()
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
    public function testAnnotationsAdapterGetSetReader(
        string $class,
        array $params
    ): void {
        require_once dataDir2('fixtures/Annotations/TestClass.php');

        $adapter = new $class($params);

        $reader = new Reader();
        $adapter->setReader($reader);

        $expected = $reader;
        $actual   = $adapter->getReader();
        $this->assertSame($expected, $actual);

        $expected = Reader::class;
        $actual   = $adapter->getReader();
        $this->assertInstanceOf($expected, $actual);
    }
}
