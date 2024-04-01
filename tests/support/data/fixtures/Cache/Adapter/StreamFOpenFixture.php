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

namespace Phalcon\Tests1\Fixtures\Cache\Adapter;

use Phalcon\Cache\Adapter\Stream;

/**
 * Fixture for the Stream adapter
 */
class StreamFOpenFixture extends Stream
{
    /**
     * @param string $filename
     * @param string $mode
     *
     * @return resource|false
     *
     * @link https://php.net/manual/en/function.fopen.php
     */
    protected function phpFopen(string $filename, string $mode)
    {
        return false;
    }
}
