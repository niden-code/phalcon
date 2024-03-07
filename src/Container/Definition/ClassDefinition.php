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

namespace Phalcon\Container\Definition;

use Phalcon\Container\Container;
use Phalcon\Container\Exception\NotAllowed;
use Phalcon\Container\Exception\NotDefined;
use Phalcon\Container\Exception\NotFound;
use Phalcon\Container\Exception\NotInstantiated;
use Phalcon\Container\Lazy\Get as LazyGet;
use Phalcon\Container\Traits\ResolveTrait;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

use function array_key_exists;
use function array_pop;
use function class_exists;
use function end;
use function get_parent_class;
use function interface_exists;
use function is_array;
use function method_exists;

class ClassDefinition extends AbstractDefinition
{
    use ResolveTrait;

    /**
     * @var array
     */
    protected array $arguments = [];

    /**
     * @var array
     */
    protected array $collatedArguments = [];

    /**
     * @var array
     */
    protected array $collatedProperties = [];

    /**
     * @var ClassDefinition|null
     */
    protected ?ClassDefinition $inherit = null;

    /**
     * @var array<string, int>
     */
    protected array $parameterNames = [];

    /**
     * @var ReflectionParameter[]
     */
    protected array $parameters = [];

    /**
     * @var array
     */
    protected array $postConstruction = [];

    /**
     * @var array
     */
    protected array $properties = [];

    /**
     * @var ReflectionClass<object>
     */
    protected ReflectionClass $reflection;

    /**
     * @param string $id
     *
     * @throws NotFound
     */
    public function __construct(
        protected string $id
    ) {
        if (!class_exists($this->id)) {
            throw new NotFound("Class '$this->id' not found.");
        }

        $this->reflection     = new ReflectionClass($this->id);
        $this->isInstantiable = $this->reflection->isInstantiable();
        $constructor          = $this->reflection->getConstructor();

        if (null !== $constructor) {
            $this->parameters = $constructor->getParameters();

            foreach ($this->parameters as $index => $parameter) {
                $this->parameterNames[$parameter->getName()] = $index;
            }
        }
    }

    /**
     * @param int|string $parameter
     * @param mixed      $argument
     *
     * @return $this
     */
    public function argument(int | string $parameter, mixed $argument): static
    {
        $position                   = $this->parameterNames[$parameter] ?? $parameter;
        $this->arguments[$position] = $argument;
        return $this;
    }

    /**
     * @param array $arguments
     *
     * @return $this
     */
    public function arguments(array $arguments): static
    {
        $this->arguments = [];

        foreach ($arguments as $parameter => $argument) {
            $this->argument($parameter, $argument);
        }

        return $this;
    }

    /**
     * @param string|null $class
     *
     * @return $this
     * @throws NotFound
     */
    public function class(?string $class): static
    {
        if ($class === $this->id) {
            $class = null;
        }

        if ($class === null || class_exists($class)) {
            $this->class = $class;
            return $this;
        }

        throw new NotFound("Class '$class' not found.");
    }

    /**
     * @param callable $callable
     *
     * @return $this
     */
    public function decorate(callable $callable): static
    {
        $this->postConstruction[] = [__FUNCTION__, $callable];

        return $this;
    }

    /**
     * @param int|string $parameter
     *
     * @return mixed
     */
    public function getArgument(int | string $parameter): mixed
    {
        $position = $this->parameterNames[$parameter] ?? $parameter;

        return $this->arguments[$position];
    }

    /**
     * @param int|string $parameter
     *
     * @return bool
     */
    public function hasArgument(int | string $parameter): bool
    {
        $position = $this->parameterNames[$parameter] ?? $parameter;

        return array_key_exists($position, $this->arguments);
    }

    /**
     * @param Definitions|null $def
     *
     * @return $this
     */
    public function inherit(?Definitions $def): static
    {
        $parent = get_parent_class($this->id);

        if ($def === null || $parent === false) {
            $this->inherit = null;
            return $this;
        }

        $this->inherit = $def->{$parent};
        return $this;
    }

    /**
     * @param string $method
     * @param mixed  ...$arguments
     *
     * @return $this
     */
    public function method(string $method, mixed ...$arguments): static
    {
        $this->postConstruction[] = [__FUNCTION__, [$method, $arguments]];
        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return $this
     */
    public function modify(callable $callable): static
    {
        $this->postConstruction[] = [__FUNCTION__, $callable];
        return $this;
    }

    /**
     * @param Container $container
     *
     * @return object
     * @throws NotInstantiated
     */
    public function new(Container $container): object
    {
        $object = parent::new($container);

        foreach ($this->postConstruction as $postConstruction) {
            $object = $this->applyPostConstruction(
                $container,
                $object,
                $postConstruction,
            );
        }

        return $object;
    }

    /**
     * @param array $properties
     *
     * @return $this
     */
    public function properties(array $properties): static
    {
        $this->properties = [];

        foreach ($properties as $name => $value) {
            $this->property($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function property(string $name, mixed $value): static
    {
        $this->properties[$name] = $value;
        return $this;
    }

    /**
     * @param Container $container
     * @param object    $object
     * @param array     $postConstruction
     *
     * @return object
     */
    protected function applyPostConstruction(
        Container $container,
        object $object,
        array $postConstruction,
    ): object {
        [$type, $spec] = $postConstruction;

        switch ($type) {
            case 'decorate':
                /** @var object $object */
                $object = $this->invokeCallable($spec, $container, $object);

                break;

            case 'method':
                /** @var array $spec */
                [$method, $arguments] = $spec;
                $object->{$method}(...$arguments);

                break;

            case 'modify':
                $this->invokeCallable($spec, $container, $object);

                break;
        }

        return $object;
    }

    /**
     * @param int                 $position
     * @param ReflectionParameter $parameter
     *
     * @return NotDefined
     */
    protected function argumentNotDefined(
        int $position,
        ReflectionParameter $parameter,
    ): NotDefined {
        $name = $parameter->getName();
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType) {
            $kind = $type instanceof ReflectionUnionType ? 'Union' : 'Intersection';
            return new NotDefined(
                "$kind typed argument $position (\$$name) "
                . "for class definition '$this->id' is not defined.",
            );
        }

        $hint = $type->getName();

        if ($type->isBuiltin() || class_exists($hint) || interface_exists($hint)) {
            return new NotDefined(
                "Required argument $position (\$$name) "
                . "for class definition '$this->id' is not defined.",
            );
        }

        return new NotDefined(
            "Required argument $position (\$$name) "
            . "for class definition '$this->id' is typehinted as "
            . "$hint, which does not exist.",
        );
    }

    /**
     * @param Container $container
     *
     * @return void
     * @throws ReflectionException
     */
    protected function collateArguments(Container $container): void
    {
        $this->collatedArguments = [];
        $inherited               = $this->inherit === null
            ? []
            : $this->inherit->getCollatedArguments($container);

        foreach ($this->parameters as $position => $parameter) {
            $this->collatePositionalArgument($position)
            || $this->collateTypedArgument($position, $parameter, $container)
            || $this->collateInheritedArgument($position, $inherited)
            || $this->collateOptionalArgument($position, $parameter);
        }
    }

    /**
     * @param int   $position
     * @param array $inherited
     *
     * @return bool
     */
    protected function collateInheritedArgument(
        int $position,
        array $inherited,
    ): bool {
        if (array_key_exists($position, $inherited)) {
            $this->collatedArguments[$position] = $inherited[$position];
            return true;
        }

        return false;
    }

    /**
     * @param int                 $position
     * @param ReflectionParameter $parameter
     *
     * @return bool
     * @throws ReflectionException
     */
    protected function collateOptionalArgument(
        int $position,
        ReflectionParameter $parameter,
    ): bool {
        if (!$parameter->isOptional()) {
            return false;
        }

        $value = $parameter->isVariadic() ? [] : $parameter->getDefaultValue();

        $this->collatedArguments[$position] = $value;

        return true;
    }

    /**
     * @param int $position
     *
     * @return bool
     */
    protected function collatePositionalArgument(
        int $position
    ): bool {
        if (!array_key_exists($position, $this->arguments)) {
            return false;
        }

        $this->collatedArguments[$position] = $this->arguments[$position];

        return true;
    }

    /**
     * @param Container $container
     *
     * @return void
     */
    protected function collateProperties(Container $container): void
    {
        $this->collatedProperties = [];
        $inherited                = $this->inherit === null
            ? []
            : $this->inherit->getCollatedProperties($container);

        foreach ($inherited as $name => $value) {
            $this->collatedProperties[$name] = $value;
        }

        foreach ($this->properties as $name => $value) {
            $this->collatedProperties[$name] = $value;
        }
    }

    /**
     * @param int                 $position
     * @param ReflectionParameter $parameter
     * @param Container           $container
     *
     * @return bool
     */
    protected function collateTypedArgument(
        int $position,
        ReflectionParameter $parameter,
        Container $container,
    ): bool {
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType) {
            return false;
        }

        $type = $type->getName();

        // explicit
        if (array_key_exists($type, $this->arguments)) {
            $this->collatedArguments[$position] = $this->arguments[$type];
            return true;
        }

        // implicit
        if ($container->has($type)) {
            $this->collatedArguments[$position] = new LazyGet($type);
            return true;
        }

        return false;
    }

    /**
     * @param array $arguments
     *
     * @return array
     * @throws NotAllowed
     */
    protected function expandVariadic(array $arguments): array
    {
        $lastParameter = end($this->parameters);

        if (false === $lastParameter || true !== $lastParameter->isVariadic()) {
            return $arguments;
        }

        $results      = $arguments;
        $lastArgument = end($results);

        if (!is_array($lastArgument)) {
            $type     = gettype($lastArgument);
            $position = $lastParameter->getPosition();
            $name     = $lastParameter->getName();

            throw new NotAllowed(
                "Variadic argument $position (\$$name) "
                . "for class definition '$this->id' is defined as $type, "
                . "but should be an array of variadic values.",
            );
        }

        array_pop($results);

        foreach ($lastArgument as $value) {
            $results[] = $value;
        }

        return $results;
    }

    /**
     * @param Container $container
     *
     * @return array
     * @throws ReflectionException
     */
    protected function getCollatedArguments(Container $container): array
    {
        if (true === empty($this->collatedArguments)) {
            $this->collateArguments($container);
        }

        return $this->collatedArguments;
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected function getCollatedProperties(Container $container): array
    {
        if (true === empty($this->collatedProperties)) {
            $this->collateProperties($container);
        }

        return $this->collatedProperties;
    }

    /**
     * @param Container $container
     *
     * @return object
     * @throws NotAllowed
     * @throws NotDefined
     * @throws ReflectionException
     */
    protected function instantiate(Container $container): object
    {
        if ($this->factory !== null) {
            $factory = $this->resolveArgument($container, $this->factory);

            /** @var object */
            return $this->invokeCallable($factory, $container);
        }

        if ($this->class !== null) {
            /** @var object */
            return $container->new($this->class);
        }

        $object     = $this->reflection->newInstanceWithoutConstructor();
        $properties = $this->getCollatedProperties($container);

        foreach ($properties as $name => $value) {
            $prop = $this->reflection->getProperty($name);
            $prop->setAccessible(true);
            $prop->setValue($object, $value);
        }

        if (!method_exists($object, '__construct')) {
            return $object;
        }

        $arguments = $this->getCollatedArguments($container);

        foreach ($this->parameters as $position => $parameter) {
            if (!array_key_exists($position, $arguments)) {
                throw $this->argumentNotDefined($position, $parameter);
            }

            $arguments[$position] = $this->resolveArgument(
                $container,
                $arguments[$position],
            );
        }

        $arguments = $this->expandVariadic($arguments);
        $object->__construct(...$arguments);

        return $object;
    }

    /**
     * @param array $arguments
     */
    protected function invokeCallable(mixed $callable, ...$arguments): mixed
    {
        /** @var callable $callable */
        return $callable(...$arguments);
    }
}
