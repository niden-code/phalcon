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
use Phalcon\Container\Resolver\Lazy\ArrayValues;
use Phalcon\Container\Resolver\Lazy\Call;
use Phalcon\Container\Resolver\Lazy\CallableGet;
use Phalcon\Container\Resolver\Lazy\CallableNew;
use Phalcon\Container\Resolver\Lazy\CsEnv;
use Phalcon\Container\Resolver\Lazy\Env;
use Phalcon\Container\Resolver\Lazy\EnvDefault;
use Phalcon\Container\Resolver\Lazy\FunctionCall;
use Phalcon\Container\Resolver\Lazy\Get;
use Phalcon\Container\Resolver\Lazy\GetCall;
use Phalcon\Container\Resolver\Lazy\LazyFactory;
use Phalcon\Container\Resolver\Lazy\NewCall;
use Phalcon\Container\Resolver\Lazy\NewInstance;
use Phalcon\Container\Resolver\Lazy\StaticCall;
use Phalcon\Tests\Benchmarks\Container\Fake\BenchService;

/**
 * @BeforeMethods({"setUp"})
 */
class LazyBench
{
    private Container $container;
    private ArrayValues $lazyArrayValues;
    private Call $lazyCall;
    private CallableGet $lazyCallableGet;
    private CallableNew $lazyCallableNew;
    private CsEnv $lazyCsEnv;
    private Env $lazyEnv;
    private EnvDefault $lazyEnvDefault;
    private FunctionCall $lazyFunctionCall;
    private Get $lazyGet;
    private GetCall $lazyGetCall;
    private NewCall $lazyNewCall;
    private NewInstance $lazyNewInstance;
    private StaticCall $lazyStaticCall;

    public function setUp(): void
    {
        $this->container = new Container();
        $this->container->set(BenchService::class, BenchService::class);
        $this->container->get(BenchService::class);

        putenv('BENCH_VAR=bench_value');
        putenv('BENCH_CSV_VAR=a,b,c');
        $_ENV['BENCH_VAR']     = 'bench_value';
        $_ENV['BENCH_CSV_VAR'] = 'a,b,c';

        $this->lazyArrayValues  = LazyFactory::arrayValues([1, 2, 3]);
        $this->lazyCall         = LazyFactory::call(static fn ($c) => new BenchService());
        $this->lazyCallableGet  = LazyFactory::callableGet(BenchService::class);
        $this->lazyCallableNew  = LazyFactory::callableNew(BenchService::class);
        $this->lazyCsEnv        = LazyFactory::csEnv('BENCH_CSV_VAR');
        $this->lazyEnv          = LazyFactory::env('BENCH_VAR');
        $this->lazyEnvDefault   = LazyFactory::envDefault('BENCH_MISSING', 'fallback');
        $this->lazyFunctionCall = LazyFactory::functionCall('strtolower', ['HELLO']);
        $this->lazyGet          = LazyFactory::get(BenchService::class);
        $this->lazyGetCall      = LazyFactory::getCall(BenchService::class, 'getValue', []);
        $this->lazyNewCall      = LazyFactory::newCall(BenchService::class, 'getValue', []);
        $this->lazyNewInstance  = LazyFactory::newInstance(BenchService::class);
        $this->lazyStaticCall   = LazyFactory::staticCall(BenchService::class, 'staticValue', []);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyArrayValues(): void
    {
        $this->lazyArrayValues->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyCall(): void
    {
        $this->lazyCall->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyCallableGet(): void
    {
        $this->lazyCallableGet->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyCallableNew(): void
    {
        $this->lazyCallableNew->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyCsEnv(): void
    {
        $this->lazyCsEnv->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyEnv(): void
    {
        $this->lazyEnv->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyEnvDefault(): void
    {
        $this->lazyEnvDefault->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyFunctionCall(): void
    {
        $this->lazyFunctionCall->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyGet(): void
    {
        $this->lazyGet->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyGetCall(): void
    {
        $this->lazyGetCall->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyNewCall(): void
    {
        $this->lazyNewCall->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyNewInstance(): void
    {
        $this->lazyNewInstance->resolve($this->container);
    }

    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchLazyStaticCall(): void
    {
        $this->lazyStaticCall->resolve($this->container);
    }
}
