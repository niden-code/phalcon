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

use stdClass;

class Union
{
    /**
     * @param mixed[]|stdClass $union
     */
    public function __construct(
        public array|stdClass $union
    ) {
    }
}
