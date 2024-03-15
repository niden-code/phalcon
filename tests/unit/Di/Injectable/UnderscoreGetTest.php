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

namespace Phalcon\Tests\Unit\Di\Injectable;

use Exception;
use Phalcon\Di\Di;
use Phalcon\Tests1\Fixtures\Di\InjectableComponent;
use RuntimeException;
use stdClass;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function restore_error_handler;
use function set_error_handler;
use function spl_object_hash;

use const PHP_OS_FAMILY;

final class UnderscoreGetTest extends AbstractUnitTestCase
{
//    /**
//     * Unit Tests Phalcon\Di\Injectable :: __get() - exception
//     *
//     * @return void
//     *
//     * @throws Exception
//     *
//     * @author Phalcon Team <team@phalcon.io>
//     * @since  2019-09-09
//     */
//    public function testDiInjectableUnderscoreGetException(): void
//    {
//        if (PHP_OS_FAMILY === 'Windows') {
//            $this->markTestSkipped('Need to fix Windows new lines...');
//        }
//
//        Di::reset();
//        $container = new Di();
//
//        $container->set('component', InjectableComponent::class);
//        $component = $container->get('component');
//
//        set_error_handler(
//            function (int $number, string $message) {
//                throw new RuntimeException($message, $number);
//            }
//        );
//
//        $this->expectException(RuntimeException::class);
//        $this->expectExceptionMessage('Access to undefined property unknown');
//
//        $actual = $component->unknown;
//
//        restore_error_handler();
//    }

    /**
     * Unit Tests Phalcon\Di\Injectable :: __get()/__isset()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiInjectableUnderscoreGetIsset(): void
    {
        Di::reset();
        $container = new Di();

        $stdClass = function () {
            return new stdClass();
        };

        $container->set('std', $stdClass);
        $container->set('component', InjectableComponent::class);

        $component = $container->get('component');
        $actual    = $component->getDI();
        $this->assertSame($container, $actual);

        $class  = stdClass::class;
        $actual = $component->std;
        $this->assertInstanceOf($class, $actual);

        $expected = spl_object_hash($container);
        $actual   = spl_object_hash($component->di);
        $this->assertSame($expected, $actual);

        $actual = isset($component->di);
        $this->assertTrue($actual);

        $actual = isset($component->component);
        $this->assertTrue($actual);

        $actual = isset($component->std);
        $this->assertTrue($actual);
    }
}
