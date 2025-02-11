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
 * @link    https://github.com/atlasphp/Atlas.Table
 * @license https://github.com/atlasphp/Atlas.Table/blob/1.x/LICENSE.md
 */

declare(strict_types=1);

namespace Phalcon\DataMapper\Table\Exception;

use Phalcon\DataMapper\Table\AbstractRow;

use function get_class;

/**
 * Exception when an class is already set in the IdentityMap
 */
class RowAlreadyIdentityMappedException extends Exception
{
    /**
     * @param AbstractRow $row
     * @param string      $serial
     */
    public function __construct(AbstractRow $row, string $serial)
    {
        $class = get_class($row);

        parent::__construct(
            "[$class] with serial [$serial] already exists in the IdentityMap."
        );
    }
}
