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
 * Exception when an unexpected type is present in a method
 */
class UnexpectedTypeException extends Exception
{
    /**
     * @param string $label
     * @param string $expected
     * @param string $actual
     */
    public function __construct(string $label, string $expected, string $actual)
    {
        parent::__construct(
            "Expected [$label] of type [$expected], [actual: $actual]."
        );
    }
}
