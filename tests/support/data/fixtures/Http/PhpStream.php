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

namespace Phalcon\Tests1\Fixtures\Http;

use Phalcon\Tests\Support\HelperTrait;

use function outputDir2;

/**
 * @link https://php.net/manual/en/class.streamwrapper.php
 * @link https://php.net/manual/en/stream.streamwrapper.example-1.php
 *
 * @codingStandardsIgnoreFile
 */
class PhpStream
{
    use HelperTrait;

    /**
     * @var string
     */
    protected string $context = '';

    /**
     * @var int
     */
    protected int $index = 0;

    /**
     * @var int
     */
    protected int $length = 0;

    /**
     * @var string
     */
    protected string $data = '';

    /**
     * @var string
     */
    protected string $outputDir = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        if (file_exists($this->getBufferFilename())) {
            $this->data = file_get_contents(
                $this->getBufferFilename()
            );
        }

        $this->index  = 0;
        $this->length = strlen($this->data);
    }

    public function stream_close()
    {
    }

    public function stream_eof()
    {
        return ($this->index >= $this->length);
    }

    public function stream_flush()
    {
        return true;
    }

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    public function stream_read($count)
    {
        if (null === $this->length) {
            $this->length = strlen($this->data);
        }

        $length = min(
            $count,
            $this->length - $this->index
        );

        $data        = substr($this->data, $this->index);
        $this->index = $this->index + $length;

        return $data;
    }

    public function stream_seek($offset, $whence)
    {
        if (null === $this->length) {
            $this->length = strlen($this->data);
        }

        switch ($whence) {
            case SEEK_SET:
                if ($offset < $this->length && $offset >= 0) {
                    $this->index = $offset;

                    return true;
                } else {
                    return false;
                }

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->index += $offset;

                    return true;
                } else {
                    return false;
                }

            case SEEK_END:
                if ($this->length + $offset >= 0) {
                    $this->index = $this->length + $offset;

                    return true;
                } else {
                    return false;
                }

            default:
                return false;
        }
    }

    public function stream_stat()
    {
        return [];
    }

    public function stream_tell()
    {
        return $this->index;
    }


    public function stream_write($data)
    {
        return file_put_contents(
            $this->getBufferFilename(),
            $data
        );
    }

    public function unlink()
    {
        if (file_exists($this->getBufferFilename())) {
            unlink(
                $this->getBufferFilename()
            );
        }

        $this->data   = '';
        $this->index  = 0;
        $this->length = 0;
    }

    protected function getBufferFilename(): string
    {
        return $this->outputDir('stream/php_input.txt');
    }
}
