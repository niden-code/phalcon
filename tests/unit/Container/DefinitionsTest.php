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

use Phalcon\Container\Definition\ClassDefinition;
use Phalcon\Container\Definition\Definitions;
use Phalcon\Container\Definition\InterfaceDefinition;
use Phalcon\Container\Exception\NotFound;
use Phalcon\Container\Lazy\ArrayValues;
use Phalcon\Container\Lazy\Call;
use Phalcon\Container\Lazy\CallableGet;
use Phalcon\Container\Lazy\CallableNew;
use Phalcon\Container\Lazy\Env;
use Phalcon\Container\Lazy\FunctionCall;
use Phalcon\Container\Lazy\Get;
use Phalcon\Container\Lazy\GetCall;
use Phalcon\Container\Lazy\IncludeFile;
use Phalcon\Container\Lazy\NewCall;
use Phalcon\Container\Lazy\NewInstance;
use Phalcon\Container\Lazy\RequireFile;
use Phalcon\Container\Lazy\StaticCall;
use Phalcon\Container\Provider\ProviderInterface;
use Phalcon\Tests1\Fixtures\Container\ChildClass;
use Phalcon\Tests1\Fixtures\Container\ChildInterface;
use PHPUnit\Framework\TestCase;

class DefinitionsTest extends TestCase
{
    protected Definitions $def;

    /**
     * @return void
     */
    public function testContainerDefinitionsAliasedEntries(): void
    {
        $this->def->{'one.copy'} = $this->def->{ChildClass::class};
        $this->assertSame($this->def->{ChildClass::class}, $this->def->{'one.copy'});
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsArray(): void
    {
        $this->assertInstanceOf(ArrayValues::class, $this->def->array(['one']));
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsCall(): void
    {
        $this->assertInstanceOf(
            Call::class,
            $this->def->call(function ($container) {
                return true;
            })
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsCallableGet(): void
    {
        $this->assertInstanceOf(
            CallableGet::class,
            $this->def->callableGet(ChildClass::class),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsCallableNew(): void
    {
        $this->assertInstanceOf(
            CallableNew::class,
            $this->def->callableNew(ChildClass::class),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsClonedEntries(): void
    {
        $this->def->{'one.clone'} = clone $this->def->{ChildClass::class};
        $this->assertNotSame($this->def->{ChildClass::class}, $this->def->{'one.clone'});
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsCsEnv(): void
    {
        $this->assertInstanceOf(Env::class, $this->def->csEnv('CAPSULE_DI_FOO'));
        $this->assertInstanceOf(
            Env::class,
            $this->def->csEnv('CAPSULE_DI_FOO', 'int'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsEnv(): void
    {
        $this->assertInstanceOf(Env::class, $this->def->env('CAPSULE_DI_FOO'));
        $this->assertInstanceOf(
            Env::class,
            $this->def->env('CAPSULE_DI_FOO', 'int'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsFunctionCall(): void
    {
        $this->assertInstanceOf(
            FunctionCall::class,
            $this->def->functionCall('Phalcon\Container\fake', 'ten'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsGet(): void
    {
        $this->assertInstanceOf(Get::class, $this->def->get(ChildClass::class));
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsGetCall(): void
    {
        $this->assertInstanceOf(
            GetCall::class,
            $this->def->getCall(ChildClass::class, 'getValue'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsInclude(): void
    {
        $this->assertInstanceOf(
            IncludeFile::class,
            $this->def->include('include_file.php'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsNamedEntries(): void
    {
        $this->def->one1 = new ClassDefinition(ChildClass::class);
        $this->assertInstanceOf(ClassDefinition::class, $this->def->one1);
        $this->def->one2 = new ClassDefinition(ChildClass::class);
        $this->assertInstanceOf(ClassDefinition::class, $this->def->one2);
        $this->assertNotSame($this->def->one1, $this->def->one2);
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsNew(): void
    {
        $this->assertInstanceOf(
            NewInstance::class,
            $this->def->new(ChildClass::class),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsNewCall(): void
    {
        $this->assertInstanceOf(
            NewCall::class,
            $this->def->newCall(ChildClass::class, 'getValue'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsRequire(): void
    {
        $this->assertInstanceOf(
            RequireFile::class,
            $this->def->require('include_file.php'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsStaticCall(): void
    {
        $this->assertInstanceOf(
            StaticCall::class,
            $this->def->staticCall(ChildClass::class, 'staticFake', 'ten'),
        );
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsGetInterface(): void
    {
        $def = $this->def->{ChildInterface::class};
        $this->assertInstanceOf(InterfaceDefinition::class, $def);
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsMagicObjects(): void
    {
        // not defined, but exists
        $this->assertFalse(isset($this->def->{ChildClass::class}));

        // define it
        $def1 = $this->def->{ChildClass::class};
        $this->assertInstanceOf(ClassDefinition::class, $def1);

        // now it is defined
        $this->assertTrue(isset($this->def->{ChildClass::class}));

        // make sure they are shared instances
        $def2 = $this->def->{ChildClass::class};
        $this->assertInstanceOf(ClassDefinition::class, $def2);
        $this->assertSame($def1, $def2);

        // undefine it
        unset($this->def->{ChildClass::class});
        $this->assertFalse(isset($this->def->{ChildClass::class}));

        // does not exist
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('NoSuchClass');
        $noSuchClass = $this->def->NoSuchClass;
    }

    /**
     * @return void
     */
    public function testContainerDefinitionsMagicValues(): void
    {
        // not defined
        $this->assertFalse(isset($this->def->one));

        $this->def->one = 'ten';
        $this->assertTrue(isset($this->def->one));
        $this->assertSame('ten', $this->def->one);

        unset($this->def->one);

        $this->assertFalse(isset($this->def->one));
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('one');
        $one = $this->def->one;
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->def = new Definitions();
    }
}
