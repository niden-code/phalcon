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

namespace Phalcon\Tests\Benchmarks\Container;

use Phalcon\Container\ContainerFactory;
use Phalcon\Tests\Benchmarks\Container\Fake\BenchServiceProvider;

/**
 * @BeforeMethods({"setUp"})
 */
class ContainerFactoryBench
{
    private ContainerFactory $addFactory;
    private ContainerFactory $emptyFactory;
    private ContainerFactory $factory;

    public function setUp(): void
    {
        $this->factory = new ContainerFactory();
        $this->factory->addProvider(new BenchServiceProvider());

        $this->emptyFactory = new ContainerFactory();
        $this->addFactory   = new ContainerFactory();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchAddProvider(): void
    {
        $this->addFactory->addProvider(new BenchServiceProvider());
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNewContainer(): void
    {
        $this->factory->newContainer();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNewContainerNoProviders(): void
    {
        $this->emptyFactory->newContainer();
    }
}
