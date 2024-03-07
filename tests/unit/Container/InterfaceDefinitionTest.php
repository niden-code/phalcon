<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Tests\Unit\Container;

use Phalcon\Container\Container;
use Phalcon\Container\Definition\InterfaceDefinition;
use Phalcon\Container\Exception\NotDefined;
use Phalcon\Container\Exception\NotFound;
use Phalcon\Container\Exception\NotInstantiated;
use Phalcon\Tests1\Fixtures\Container\ChildClass;
use Phalcon\Tests1\Fixtures\Container\ChildInterface;
use stdClass;

class InterfaceDefinitionTest extends DefinitionTestCase
{
    /**
     * @return void
     * @throws NotFound
     * @throws NotInstantiated
     */
    public function testContainerClass(): void
    {
        $definition = new InterfaceDefinition(ChildInterface::class);
        $definition->class(stdClass::class);
        $this->assertInstanceOf(stdClass::class, $this->actual($definition));
    }

    /**
     * @return void
     */
    public function testContainerClassNotDefined(): void
    {
        $definition = new InterfaceDefinition(ChildInterface::class);
        $this->assertNotInstantiable(
            $definition,
            [
                [
                    NotDefined::class,
                    "Class/factory for interface definition "
                    . "'Phalcon\Tests1\Fixtures\Container\ChildInterface' "
                    . "not set.",
                ],
            ],
        );
    }

    /**
     * @return void
     * @throws NotFound
     */
    public function testContainerClassNotFound(): void
    {
        $definition = new InterfaceDefinition(ChildInterface::class);
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage("Class 'NoSuchClass' not found.");
        $definition->class('NoSuchClass');
    }

    /**
     * @return void
     */
    public function testContainerConstructorNotInterface(): void
    {
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage(
            "Interface 'Phalcon\Tests1\Fixtures\Container\ChildClass' not found."
        );
        $definition = new InterfaceDefinition(ChildClass::class);
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerFactory(): void
    {
        $definition = new InterfaceDefinition(ChildInterface::class);
        $definition->factory(function (Container $container) {
            return new stdClass();
        });
        $this->assertInstanceOf(stdClass::class, $this->actual($definition));
    }
}
