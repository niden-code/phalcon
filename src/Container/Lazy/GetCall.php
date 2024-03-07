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

class GetCall extends AbstractLazy
{
    /**
     * @param string|AbstractLazy $id
     * @param string              $method
     * @param array               $arguments
     */
    public function __construct(
        protected string | AbstractLazy $id,
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
        /** @var string $id */
        $id        = $this->resolveArgument($container, $this->id);
        $arguments = $this->resolveArguments($container, $this->arguments);

        return $container->get($id)->{$this->method}(...$arguments);
    }
}
