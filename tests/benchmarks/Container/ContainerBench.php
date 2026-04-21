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
use Phalcon\Tests\Benchmarks\Container\Fake\BenchService;
use Phalcon\Tests\Benchmarks\Container\Fake\BenchServiceWithDependency;

/**
 * @BeforeMethods({"setUp"})
 */
class ContainerBench
{
    private Container $container;
    private BenchService $instance;

    public function setUp(): void
    {
        $this->container = new Container();
        $this->instance  = new BenchService();

        $this->container->set('bench.scoped', BenchService::class);
        $this->container->get('bench.scoped');

        $this->container->set('bench.singleton', BenchService::class)
                        ->setLifetime(ServiceLifetime::SINGLETON);
        $this->container->get('bench.singleton');

        $this->container->set('bench.transient', BenchService::class)
                        ->setLifetime(ServiceLifetime::TRANSIENT);

        $this->container->setParameter('bench.param', 'hello');

        $this->container->set('bench.alias.target', BenchService::class);
        $this->container->setAlias('bench.alias.target', 'bench.alias');
        $this->container->get('bench.alias');

        $this->container->set('bench.tag1', BenchService::class);
        $this->container->set('bench.tag2', BenchService::class);
        $this->container->set('bench.tag3', BenchService::class);
        $this->container->setTag('bench-group', 'bench.tag1');
        $this->container->setTag('bench-group', 'bench.tag2');
        $this->container->setTag('bench-group', 'bench.tag3');
        $this->container->get('bench.tag1');
        $this->container->get('bench.tag2');
        $this->container->get('bench.tag3');

        $this->container->set(BenchService::class, BenchService::class);
        $this->container->set('bench.has', BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchBind(): void
    {
        $this->container->bind(BenchServiceWithDependency::class, BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchConstruct(): void
    {
        $container = new Container();
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchGetByTag(): void
    {
        $this->container->getByTag('bench-group');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchGetParameter(): void
    {
        $this->container->get('bench.param');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchGetScoped(): void
    {
        $this->container->get('bench.scoped');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchGetSingleton(): void
    {
        $this->container->get('bench.singleton');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchGetTransient(): void
    {
        $this->container->get('bench.transient');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchGetViaAlias(): void
    {
        $this->container->get('bench.alias');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchHas(): void
    {
        $this->container->has('bench.has');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchHasNotFound(): void
    {
        $this->container->has('nonexistent.service');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchNew(): void
    {
        $this->container->new(BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSet(): void
    {
        $this->container->set(BenchService::class, BenchService::class);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSetAlias(): void
    {
        $this->container->setAlias('bench.alias.target', 'bench.alias2');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSetInstance(): void
    {
        $this->container->setInstance('bench.instance', $this->instance, ServiceLifetime::SCOPED);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSetParameter(): void
    {
        $this->container->setParameter('bench.param', 'value');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSetTag(): void
    {
        $this->container->setTag('bench-group', 'bench.tag1');
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSetWithClosure(): void
    {
        $this->container->set('bench.closure', static fn ($c) => new BenchService());
    }
}
