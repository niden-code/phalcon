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

namespace Phalcon\Tests1\Fixtures\Storage\Adapter;

use APCUIterator;
use Phalcon\Storage\Adapter\Apcu;

/**
 * Fixture for the Apcu adapter
 */
class ApcuIteratorFixture extends Apcu
{
    /**
     * @param string $pattern
     *
     * @return APCUIterator|false
     */
    protected function phpApcuIterator(string $pattern)
    {
        return false;
    }
}
