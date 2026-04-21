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

use Phalcon\Container\Container;
use Phalcon\Container\Definition\ServiceLifetime;
use Phalcon\Container\Resolver\Resolver;
use Phalcon\Tests\Benchmarks\Container\Fake\BenchService;
use Phalcon\Tests\Benchmarks\Container\Fake\BenchServiceWithDependency;
use Phalcon\Tests\Benchmarks\Container\Fake\BenchServiceWithTwoDeps;

/**
 * @BeforeMethods({"setUp"})
 */
class ResolverBench
{
    private Container $container;
    private Container $noAutowireContainer;
    private Resolver $resolver;

    public function setUp(): void
    {
        $this->resolver = new Resolver();

        $this->container = new Container();
        $this->container->set(BenchService::class, BenchService::class)
                        ->setLifetime(ServiceLifetime::TRANSIENT);
        $this->container->set(BenchServiceWithDependency::class, BenchServiceWithDependency::class)
                        ->setLifetime(ServiceLifetime::TRANSIENT);
        $this->container->set(BenchServiceWithTwoDeps::class, BenchServiceWithTwoDeps::class)
                        ->setLifetime(ServiceLifetime::TRANSIENT);

        $this->noAutowireContainer = new Container();
        $this->noAutowireContainer->setAutowire(false);
        $this->noAutowireContainer->set(BenchService::class, BenchService::class);
        $this->noAutowireContainer->get(BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchAutowireDisabled(): void
    {
        $this->noAutowireContainer->get(BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchAutowireOneDep(): void
    {
        $this->container->get(BenchServiceWithDependency::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchAutowireSimple(): void
    {
        $this->container->get(BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchAutowireTwoDeps(): void
    {
        $this->container->get(BenchServiceWithTwoDeps::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchIsResolvableClass(): void
    {
        $this->resolver->isResolvableClass(BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchResolveClass(): void
    {
        $this->resolver->resolveClass($this->container, BenchService::class, []);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchResolveClassWithArgs(): void
    {
        $this->resolver->resolveClass($this->container, BenchServiceWithDependency::class, []);
    }
}
