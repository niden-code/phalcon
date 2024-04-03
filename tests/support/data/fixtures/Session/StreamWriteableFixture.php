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

namespace Phalcon\Tests1\Fixtures\Session;

use Phalcon\Session\Adapter\Noop;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Exception;
use Phalcon\Traits\Php\FileTrait;
use Phalcon\Traits\Php\InfoTrait;
use Phalcon\Traits\Php\IniTrait;

use function file_exists;
use function rtrim;

use const DIRECTORY_SEPARATOR;

class StreamWriteableFixture extends Stream
{
    /**
     * Tells whether the filename is writable
     *
     * @param string $filename
     *
     * @return bool
     *
     * @link https://php.net/manual/en/function.is-writable.php
     */
    protected function phpIsWritable(string $filename): bool
    {
        return false;
    }
}
