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
use Phalcon\Container\Exception\NotDefined;

class Env extends AbstractLazy
{
    /**
     * @param string      $varname
     * @param string|null $vartype
     */
    public function __construct(
        protected string $varname,
        protected ?string $vartype = null,
    ) {
    }

    /**
     * @param Container $container
     *
     * @return mixed
     * @throws NotDefined
     */
    public function __invoke(Container $container): mixed
    {
        $value = $this->getEnv();

        if (null !== $this->vartype) {
            settype($value, $this->vartype);
        }

        return $value;
    }

    /**
     * @return string
     * @throws NotDefined
     */
    protected function getEnv(): string
    {
        $env = getenv();

        if (true !== array_key_exists($this->varname, $env)) {
            throw new NotDefined(
                "Evironment variable '$this->varname' is not defined.",
            );
        }

        return $env[$this->varname];
    }
}
