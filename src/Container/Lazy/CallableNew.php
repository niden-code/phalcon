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

use Closure;
use Phalcon\Container\Container;

class CallableNew extends AbstractLazy
{
    /**
     * @param string|AbstractLazy $id
     */
    public function __construct(
        protected string | AbstractLazy $id
    ) {
    }

    /**
     * @param Container $container
     *
     * @return Closure
     */
    public function __invoke(Container $container): Closure
    {
        return function () use ($container) {
            /** @var string $id */
            $id = $this->resolveArgument($container, $this->id);

            return $container->new($id);
        };
    }
}
