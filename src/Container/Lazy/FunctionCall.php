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

class FunctionCall extends AbstractLazy
{
    /**
     * @param string $function
     * @param array  $arguments
     */
    public function __construct(
        protected string $function,
        protected array $arguments
    ) {
    }

    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function __invoke(Container $container): mixed
    {
        $arguments = $this->resolveArguments($container, $this->arguments);

        /** @var callable $function */
        $function = $this->function;

        return $function(...$arguments);
    }
}
