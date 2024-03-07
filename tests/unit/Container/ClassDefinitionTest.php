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
use Phalcon\Container\Definition\ClassDefinition;
use Phalcon\Container\Exception\NotAllowed;
use Phalcon\Container\Exception\NotDefined;
use Phalcon\Container\Exception\NotFound;
use Phalcon\Container\Exception\NotInstantiated;
use Phalcon\Container\Lazy\Call;
use Phalcon\Tests1\Fixtures\Container\ChildClass;
use Phalcon\Tests1\Fixtures\Container\BadHint;
use Phalcon\Tests1\Fixtures\Container\ConstructorClass;
use Phalcon\Tests1\Fixtures\Container\ConstructorDefaultValue;
use Phalcon\Tests1\Fixtures\Container\ConstructorObjects;
use Phalcon\Tests1\Fixtures\Container\GrandParentClass;
use Phalcon\Tests1\Fixtures\Container\Optional;
use Phalcon\Tests1\Fixtures\Container\ParentClass;
use Phalcon\Tests1\Fixtures\Container\Union;
use stdClass;

use function uniqid;

class ClassDefinitionTest extends DefinitionTestCase
{
    /**
     * @return void
     */
    public function testContainerArgument(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $this->assertFalse($definition->hasArgument(0));

        $definition->argument(0, 'one');
        $this->assertTrue($definition->hasArgument(0));

        $this->assertSame('one', $definition->getArgument(0));
    }

    /**
     * @return void
     */
    public function testContainerArgumentLazy(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->argument(
            0,
            new Call(
                function ($container) {
                    return 'lazy';
                }
            )
        );

        /** @var ChildClass $actual */
        $actual = $this->actual($definition);
        $this->assertSame('lazy', $actual->one);
    }

    /**
     * @return void
     */
    public function testContainerArgumentMissingRequired(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $this->assertNotInstantiable(
            $definition,
            [
                [
                    NotDefined::class,
                    "Required argument 0 (\$one) for class definition "
                    . "'Phalcon\Tests1\Fixtures\Container\ChildClass' "
                    . "is not defined.",
                ],
            ],
        );
    }

    /**
     * @return void
     */
    public function testContainerArgumentMissingRequiredNullable(): void
    {
        $definition = new ClassDefinition(ConstructorObjects::class);
        $this->assertNotInstantiable(
            $definition,
            [
                [
                    NotInstantiated::class,
                    "Could not instantiate "
                    . "Phalcon\Tests1\Fixtures\Container\ChildClass",
                ],
                [
                    NotDefined::class,
                    "Required argument 0 (\$one) for class definition "
                    . "'Phalcon\Tests1\Fixtures\Container\ChildClass' "
                    . "is not defined.",
                ],
            ],
        );
    }

    /**
     * @return void
     */
    public function testContainerArgumentMissingUnionType(): void
    {
        $definition = new ClassDefinition(Union::class);
        $this->assertNotInstantiable(
            $definition,
            [
                [
                    NotDefined::class,
                    "Union typed argument 0 (\$union) for class definition "
                    . "'Phalcon\Tests1\Fixtures\Container\Union' "
                    . "is not defined.",
                ],
            ],
        );
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentNamed(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->argument('one', 'ten');

        $this->assertInstanceOf(ChildClass::class, $this->actual($definition));
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentNamedType(): void
    {
        $definition = new ClassDefinition(ConstructorClass::class);

        /** @var ConstructorClass $one */
        $one = $this->actual($definition);
        $this->assertInstanceOf(ConstructorClass::class, $one);

        /** @var ConstructorClass $two */
        $two = $this->actual($definition);
        $this->assertSame($one->std, $two->std);
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentNumbered(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->argument(0, 'one');

        $this->assertInstanceOf(ChildClass::class, $this->actual($definition));
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentOptional(): void
    {
        $definition = new ClassDefinition(Optional::class);
        $definition->argument(0, 'val0');
        $definition->argument(2, ['val2a', 'val2b', 'val2c']);

        $this->assertInstanceOf(Optional::class, $this->actual($definition));
    }

    /**
     * @return void
     */
    public function testContainerArgumentTypeDoesNotExist(): void
    {
        $definition = new ClassDefinition(BadHint::class);
        $this->assertNotInstantiable(
            $definition,
            [
                [
                    NotDefined::class,
                    "Required argument 0 (\$nonesuch) for class definition "
                    . "'Phalcon\Tests1\Fixtures\Container\BadHint' is "
                    . "typehinted as Phalcon\Tests1\Fixtures\Container\Nonesuch, "
                    . "which does not exist.",
                ],
            ],
        );
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentTyped(): void
    {
        $definition = new ClassDefinition(ConstructorClass::class);
        $definition->argument(
            stdClass::class,
            $this->definitions->new(stdClass::class),
        );

        $this->assertInstanceOf(ConstructorClass::class, $this->actual($definition));
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentUnionType(): void
    {
        $definition = new ClassDefinition(Union::class);
        $expected     = ['arrayval'];
        $definition->argument(0, $expected);

        /** @var Union $actual */
        $actual = $this->actual($definition);
        $this->assertSame($expected, $actual->union);
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentVariadic(): void
    {
        $value1 = uniqid('one-');
        $value2 = uniqid('two-');
        $value3 = [
            uniqid('three-'),
            uniqid('three-'),
            uniqid('three-')
        ];

        $definition = new ClassDefinition(Optional::class);
        $definition->arguments([$value1, $value2, $value3]);

        $expected   = $value3;
        /** @var Optional $actual */
        $actual = $this->actual($definition);
        $this->assertSame($expected, $actual->three);
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentVariadicOmitted(): void
    {
        $value1 = uniqid('val-');
        $value2 = uniqid('val-');
        $definition = new ClassDefinition(Optional::class);
        $definition->arguments([$value1, $value2]);

        /** @var Optional $actual */
        $actual = $this->actual($definition);
        $this->assertSame([], $actual->three);
    }

    /**
     * @return void
     */
    public function testContainerArgumentVariadicWrong(): void
    {
        $definition = new ClassDefinition(Optional::class);
        $definition->arguments(['va10', 'one', 'not-an-array']);
        $this->assertNotInstantiable(
            $definition,
            [
                [
                    NotAllowed::class,
                    "Variadic argument 2 (\$three) for class definition "
                    . "'Phalcon\Tests1\Fixtures\Container\Optional' is "
                    . "defined as string, but should be an array of "
                    . "variadic values.",
                ],
            ],
        );
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerArgumentLatestTakesPrecedence(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->arguments([0 => 'valbefore', 'one' => 'valafter']);

        /** @var ChildClass $actual */
        $actual = $this->actual($definition);
        $this->assertSame('valafter', $actual->one);
        $definition->arguments(['one' => 'valbefore', 0 => 'valafter']);

        /** @var ChildClass $actual */
        $actual = $this->actual($definition);
        $this->assertSame('valafter', $actual->one);
    }

    /**
     * @return void
     * @throws NotFound
     * @throws NotInstantiated
     */
    public function testContainerArgumentAlternativeClass(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->class(stdClass::class);
        $this->assertTrue($definition->isInstantiable($this->container));
        $this->assertInstanceOf(stdClass::class, $this->actual($definition));
    }

    /**
     * @return void
     */
    public function testContainerArgumentNoSuchClass(): void
    {
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage("Class 'NoSuchClass' not found.");
        $definition = new ClassDefinition('NoSuchClass');
    }

    /**
     * @return void
     * @throws NotFound
     */
    public function testContainerArgumentNotFound(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage("Class 'NoSuchClass' not found.");
        $definition->class('NoSuchClass');
    }

    /**
     * @return void
     * @throws NotFound
     * @throws NotInstantiated
     */
    public function testContainerArgumentSameAsId(): void
    {
        $definition = new ClassDefinition(stdClass::class);
        $definition->class(stdClass::class);
        $this->assertInstanceOf(stdClass::class, $this->actual($definition));
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerFactory(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->factory(
            function ($container) {
                return new stdClass();
            }
        );
        $this->assertTrue($definition->isInstantiable($this->container));
        $this->assertInstanceOf(stdClass::class, $this->actual($definition));
    }

    /**
     * @return void
     */
    public function testContainerInherit(): void
    {
        $def = $this->definitions;
        $def->{ChildClass::class}->argument('one', 'parent');
        $def->{ChildClass::class}->property('property', 'prop1value');
        $def->{ParentClass::class}->inherit($def)->argument('two', 'child');

        /** @var ParentClass $actual */
        $actual = $this->container->new(ParentClass::class);
        $this->assertSame('parent', $actual->one);
        $this->assertSame('child', $actual->two);
        $this->assertSame('prop1value', $actual->getProperty());

        /** @var GrandParentClass $actual */
        $actual = $this->container->new(GrandParentClass::class);
        $this->assertSame('parent', $actual->one);
        $this->assertSame('child', $actual->two);
        $this->assertSame('prop1value', $actual->getProperty());
    }

    /**
     * @return void
     */
    public function testContainerInheritDisabled(): void
    {
        $this->definitions
            ->{ChildClass::class}
            ->argument('one', 'parent')
        ;

        $this->definitions
            ->{ParentClass::class}
            ->inherit(null)
            ->argument('two', 'child')
        ;

        $definition = $this->definitions->{ParentClass::class};
        $this->assertNotInstantiable(
            $definition,
            [
                [
                    NotDefined::class,
                    "Required argument 0 (\$one) for class definition "
                    . "'Phalcon\Tests1\Fixtures\Container\ParentClass' "
                    . "is not defined.",
                ],
            ],
        );
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerNoConstructor(): void
    {
        $definition = new ClassDefinition(stdClass::class);
        $this->assertInstanceOf(stdClass::class, $this->actual($definition));
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerPostConstruction(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->arguments(['one']);
        $definition->method('append', 'two');
        $definition->modify(function (Container $container, ChildClass $one) {
            $one->append('three');
        });
        $definition->decorate(function (Container $container, ChildClass $one) {
            $one->append('four');
            return $one;
        });

        /** @var ChildClass $actual */
        $actual = $this->actual($definition);
        $this->assertSame('onetwothreefour', $actual->one);
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerProperty(): void
    {
        $definition = new ClassDefinition(ChildClass::class);
        $definition->arguments(['one']);
        $definition->property('property', 'prop1value');

        /** @var ChildClass $actual */
        $actual = $this->actual($definition);
        $this->assertSame('prop1value', $actual->getProperty());

        $definition = new ClassDefinition(ChildClass::class);
        $definition->arguments(['one']);
        $definition->properties(['property' => 'prop1value']);

        /** @var ChildClass $actual */
        $actual = $this->actual($definition);
        $this->assertSame('prop1value', $actual->getProperty());
    }

    /**
     * @return void
     * @throws NotInstantiated
     */
    public function testContainerMissingArgument(): void
    {
        $definition = new ClassDefinition(ConstructorDefaultValue::class);
        $definition->argument(1, 'twoValue');

        $this->assertInstanceOf(
            ConstructorDefaultValue::class,
            $this->actual($definition)
        );
    }
}
