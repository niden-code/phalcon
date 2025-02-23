<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Implementation of this file has been influenced by AtlasPHP
 *
 * @link    https://github.com/atlasphp/Atlas.Cli
 * @license https://github.com/atlasphp/Atlas.Cli/blob/2.x/LICENSE.md
 */

declare(strict_types=1);

namespace Phalcon\DataMapper\Cli;

use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Formatter\Line;
use Phalcon\Logger\Logger as PhalconLogger;

class Logger extends PhalconLogger
{
    public function __construct()
    {
        $name = 'datamapper-cli';
        $adapters = [
            'main' => new Stream('php://stdout'),
        ];

        parent::__construct($name, $adapters);

        $line = new Line('%message%');
        $this->getAdapter('main')->setFormatter($line);
    }
}
