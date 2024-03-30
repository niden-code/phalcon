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

namespace Phalcon\Tests1\Fixtures\Storage\Serializer;

use Phalcon\Storage\Serializer\Igbinary;

use function trigger_error;

use const E_WARNING;

class IgbinaryUnserializeWarningFixture extends Igbinary
{
    /**
     * Wrapper for `igbinary_unserialize`
     *
     * @param string $value
     *
     * @return mixed|false
     */
    protected function phpIgbinaryUnSerialize($value)
    {
        trigger_error('Unserialize Error', E_WARNING);
    }
}
