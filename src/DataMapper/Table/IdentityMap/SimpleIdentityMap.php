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

namespace Phalcon\DataMapper\Table\IdentityMap;

use Phalcon\DataMapper\Table\AbstractRow;
use Phalcon\DataMapper\Table\Exception\PrimaryValueNotScalarException;
use Phalcon\DataMapper\Table\Exception\UnexpectedTypeException;

class SimpleIdentityMap extends AbstractIdentityMap
{
    /**
     * @param mixed $primaryValue
     *
     * @return array
     * @throws PrimaryValueNotScalarException
     */
    protected function getSerialArray(mixed $primaryValue): array
    {
        $column = $this->table::PRIMARY_KEY[0];
        $serial = [
            $column => $primaryValue,
        ];

        $this->assertValueIsScalar($serial[$column], $column);

        return $serial;
    }

    /**
     * @param AbstractRow $row
     *
     * @return array
     * @throws PrimaryValueNotScalarException
     * @throws UnexpectedTypeException
     */
    protected function getSerialArrayFromRow(AbstractRow $row): array
    {
        $this->assertRow($row);
        $column = $this->table::PRIMARY_KEY[0];
        $serial = [
            $column => $row->{$column},
        ];

        $this->assertValueIsScalar($serial[$column], $column);

        return $serial;
    }
}
