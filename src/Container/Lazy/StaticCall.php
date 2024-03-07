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

namespace Phalcon\Container\Lazy;

use Phalcon\Container\Container;

class StaticCall extends AbstractLazy
{
    /**
     * @param AbstractLazy|string $class
     * @param string              $method
     * @param array               $arguments
     */
    public function __construct(
        protected AbstractLazy | string $class,
        protected string $method,
        protected array $arguments,
    ) {
    }

    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function __invoke(Container $container): mixed
    {
        $class     = $this->resolveArgument($container, $this->class);
        $arguments = $this->resolveArguments($container, $this->arguments);

        /** @var callable $staticCall */
        $staticCall = [$class, $this->method];

        return $staticCall(...$arguments);
    }
}
