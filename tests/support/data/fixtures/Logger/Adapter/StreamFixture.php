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

namespace Phalcon\Tests1\Fixtures\Logger\Adapter;

use LogicException;
use Phalcon\Logger\Adapter\AbstractAdapter;
use Phalcon\Logger\Adapter\Stream as LoggerStream;
use Phalcon\Logger\Exception;
use Phalcon\Logger\Item;
use Phalcon\Traits\Php\FileTrait;

use function fopen;
use function is_resource;
use function mb_strpos;

use const PHP_EOL;

class StreamFixture extends LoggerStream
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
