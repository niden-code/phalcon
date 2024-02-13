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

namespace MyLibrary\Tests\support\data\fixtures\Annotations\Adapter;

use Phalcon\Annotations\Adapter\Stream;

class StreamWrongDataFixture extends Stream
{
    /**
     * @param string $filename
     *
     * @return bool
     *
     * @link https://php.net/manual/en/function.file-exists.php
     */
    protected function phpFileExists(string $filename)
    {
        return true;
    }

    /**
     * @param string $filename
     *
     * @return string|false
     *
     * @link https://php.net/manual/en/function.file-get-contents.php
     */
    protected function phpFileGetContents(string $filename)
    {
        return '{a:1';
    }
}
