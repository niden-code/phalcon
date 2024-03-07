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

namespace Phalcon\Tests1\Fixtures\Container;

class Optional
{
    /**
     * @var string|null
     */
    public ?string $two;

    /**
     * @var mixed[]
     */
    public array $three;

    /**
     * @param string      $one
     * @param string|null $two
     * @param string      ...$three
     */
    public function __construct(
        public string $one,
        string $two = null,
        string ...$three,
    ) {
        $this->two   = $two;
        $this->three = $three;
    }
}
