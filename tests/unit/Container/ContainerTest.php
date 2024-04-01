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
use Phalcon\Container\Definition\Definitions;
use Phalcon\Tests\Support\AbstractUnitTestCase;
use Phalcon\Tests1\Fixtures\Container\ChildClass;
use Phalcon\Tests1\Fixtures\Container\ChildProvider;
use Phalcon\Tests1\Fixtures\Container\ConstructorObjects;
use stdClass;

class ContainerTest extends AbstractUnitTestCase
{
    protected Container $container;

    /**
     * @return void
     */
    public function testContainerCallableGet(): void
    {
        $callable = $this->container->callableGet(stdClass::class);

        $expected = $callable(stdClass::class);
        $actual   = $callable(stdClass::class);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     */
    public function testContainerCallableNew(): void
    {
        $callable = $this->container->callableNew(stdClass::class);

        $expected = $callable(stdClass::class);
        $actual   = $callable(stdClass::class);
        $this->assertNotSame($expected, $actual);
    }

    /**
     * @return void
     */
    public function testContainerGet(): void
    {
        $expected = $this->container->get(stdClass::class);
        $actual   = $this->container->get(stdClass::class);
        $this->assertSame($expected, $actual);

        $name     = 'direct';
        $expected = 'ten';
        $actual   = $this->container->get($name);
        $this->assertSame($expected, $actual);

        $name     = 'lazy';
        $expected = 'thirty';
        $actual   = $this->container->get($name);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     */
    public function testContainerHas(): void
    {
        // defined object
        $this->assertTrue($this->container->has(ChildClass::class));

        // defined value
        $this->assertTrue($this->container->has('direct'));

        // not defined but exists
        $this->assertTrue($this->container->has(ConstructorObjects::class));

        // not defined and does not exist
        $this->assertFalse($this->container->has('NoSuchClass'));
    }

    /**
     * @return void
     */
    public function testContainerNew(): void
    {
        $expected = $this->container->new(stdClass::class);
        $actual   = $this->container->new(stdClass::class);
        $this->assertNotSame($expected, $actual);

        $name     = 'direct';
        $expected = 'ten';
        $actual   = $this->container->get($name);
        $this->assertSame($expected, $actual);

        $name     = 'lazy';
        $expected = 'thirty';
        $actual   = $this->container->get($name);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->container = new Container(
            new Definitions(),
            [
                new ChildProvider(),
            ]
        );
    }
}
