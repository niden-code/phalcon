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
use Phalcon\Container\Definition\AbstractDefinition;
use Phalcon\Container\Definition\Definitions;
use Phalcon\Container\Exception\NotInstantiated;
use Phalcon\Tests\Support\AbstractUnitTestCase;

class DefinitionTestCase extends AbstractUnitTestCase
{
    /**
     * @var Container
     */
    protected Container $container;

    /**
     * @var Definitions
     */
    protected Definitions $definitions;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->definitions = new Definitions();
        $this->container   = new Container($this->definitions);
    }

    /**
     * @param AbstractDefinition $definition
     *
     * @return object
     * @throws NotInstantiated
     */
    protected function actual(AbstractDefinition $definition): object
    {
        return $definition->new($this->container);
    }

    /**
     * @param array{class-string, string}[] $expected
     */
    protected function assertNotInstantiable(
        AbstractDefinition $definition,
        array $expected,
    ): void {
        try {
            $this->actual($definition);
            $this->assertFalse(true, "Should not have been instantiated.");
        } catch (NotInstantiated $ex) {
            while (true !== empty($expected)) {
                $ex = $ex->getPrevious();
                [$expectedException, $expectedExceptionMessage] = array_shift($expected);
                $this->assertInstanceOf($expectedException, $ex);
                $this->assertSame($expectedExceptionMessage, $ex->getMessage());
            }
        }
    }
}
