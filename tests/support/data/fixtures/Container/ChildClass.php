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

class ChildClass implements ChildInterface
{
    protected string $property = '';

    /**
     * @param string $one
     * @param string $two
     */
    public function __construct(
        public string $one,
        public string $two = 'ten'
    ) {
    }

    /**
     * @param string $suffix
     *
     * @return void
     */
    public function append(string $suffix): void
    {
        $this->one .= $suffix;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->two;
    }

    /**
     * @param string $word
     *
     * @return string
     */
    public static function staticFake(string $word): string
    {
        return $word;
    }
}
