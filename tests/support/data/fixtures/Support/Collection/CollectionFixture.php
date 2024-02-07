<?php

declare(strict_types=1);

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Tests1\Fixtures\Support\Collection;

use Phalcon\Support\Collection;

/**
 * Fixture for the Collection
 */
class CollectionFixture extends Collection
{
    /**
     * @param mixed $value
     * @param int   $flags
     * @param int   $depth
     *
     * @return false|string
     *
     * @link https://php.net/manual/en/function.json-encode.php
     */
    protected function phpJsonEncode($value, int $flags = 0, int $depth = 512)
    {
        return false;
    }
}
