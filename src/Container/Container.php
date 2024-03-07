<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Implementation of this file has been adopted by Capsule DI
 *
 * @link    https://github.com/capsulephp/di
 * @license https://github.com/capsulephp/di/blob/4.x/LICENSE.md
 */

declare(strict_types=1);

namespace Phalcon\Container;

use Phalcon\Container\Definition\AbstractDefinition;
use Phalcon\Container\Definition\Definitions;
use Phalcon\Container\Provider\ProviderInterface;
use Phalcon\Container\Traits\ResolveTrait;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    use ResolveTrait;

    /**
     * @var array<string, bool>
     */
    protected array $has = [];

    /**
     * @var array<string, mixed>
     */
    protected array $registry = [];

    /**
     * @param ProviderInterface[] $providers
     */
    public function __construct(
        protected Definitions $definitions,
        iterable $providers = [],
    ) {
        foreach ($providers as $provider) {
            $provider->provide($this->definitions);
        }

        $this->registry[static::class] = $this;
    }

    /**
     * @param string $id
     *
     * @return callable
     */
    public function callableGet(string $id): callable
    {
        return function () use ($id) {
            return $this->get($id);
        };
    }

    /**
     * @param string $id
     *
     * @return callable
     */
    public function callableNew(string $id): callable
    {
        return function () use ($id) {
            return $this->new($id);
        };
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function get(string $id): mixed
    {
        if (!isset($this->registry[$id])) {
            $this->registry[$id] = $this->new($id);
        }

        return $this->registry[$id];
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        if (!isset($this->has[$id])) {
            $this->has[$id] = $this->find($id);
        }

        return $this->has[$id];
    }

    /**
     * @param string $id
     *
     * @return mixed
     */
    public function new(string $id): mixed
    {
        return $this->resolveArgument($this, $this->definitions->{$id});
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function find(string $id): bool
    {
        if (!isset($this->definitions->{$id})) {
            return $this->findImplicit($id);
        }

        if ($this->definitions->{$id} instanceof AbstractDefinition) {
            return $this->definitions->{$id}->isInstantiable($this);
        }

        return true;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    protected function findImplicit(string $id): bool
    {
        if (true !== class_exists($id) && true !== interface_exists($id)) {
            return false;
        }

        $reflection = new ReflectionClass($id);

        return $reflection->isInstantiable();
    }
}
