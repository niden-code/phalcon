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

use Phalcon\DataMapper\Pdo\Exception\ConnectionNotFound;
use Phalcon\DataMapper\Table\AbstractRow;
use Phalcon\DataMapper\Table\AbstractTable;
use Phalcon\DataMapper\Table\AbstractTableSelect;
use Phalcon\DataMapper\Table\Exception\PrimaryValueMissingException;
use Phalcon\DataMapper\Table\Exception\PrimaryValueNotScalarException;
use Phalcon\DataMapper\Table\Exception\RowAlreadyIdentityMappedException;
use Phalcon\DataMapper\Table\Exception\UnexpectedTypeException;

use function is_scalar;

abstract class AbstractIdentityMap
{
    /**
     * @var array<string, AbstractRow>
     */
    protected array $memory = [];

    /**
     * @param AbstractTable $table
     */
    public function __construct(
        protected AbstractTable $table
    ) {
    }

    /**
     * @param mixed                    $primaryValue
     * @param AbstractTableSelect|null $select
     *
     * @return AbstractRow|null
     * @throws ConnectionNotFound
     * @throws PrimaryValueMissingException
     * @throws PrimaryValueNotScalarException
     * @throws RowAlreadyIdentityMappedException
     */
    public function fetchRow(
        mixed $primaryValue,
        ?AbstractTableSelect $select = null
    ): AbstractRow | null {
        $serial = $this->getSerial($primaryValue);
        $memory = $this->getRowBySerial($serial);

        if (null !== $memory) {
            return $memory;
        }

        $select ??= $this->table->select();
        $row    = $this->table->selectRow($select, $primaryValue);

        if (null !== $row) {
            $this->setRow($row);
        }

        return $row;
    }

    /**
     * @param array                    $primaryValues
     * @param AbstractTableSelect|null $select
     *
     * @return array<string, mixed>
     * @throws ConnectionNotFound
     * @throws PrimaryValueMissingException
     * @throws PrimaryValueNotScalarException
     */
    public function fetchRows(
        array $primaryValues,
        ?AbstractTableSelect $select = null
    ): array {
        $rows = [];

        // find identity-mapped rows, adding placeholders for missing rows
        foreach ($primaryValues as $primaryValue) {
            $serial = $this->getSerial($primaryValue);
            $memory = $this->getRowBySerial($serial);

            if ($memory === null) {
                $rows[$serial]    = null;
                $missing[$serial] = $primaryValue;
            } else {
                $rows[$serial] = $memory;
            }
        }

        // early return if all rows are identity-mapped
        if (empty($missing)) {
            return array_values($rows);
        }

        $select ??= $this->table->select();

        // fetch rows missing from identity map
        foreach ($this->table->selectRows($select, $missing) as $row) {
            $serial        = $this->getSerial($row);
            $rows[$serial] = $row;
            $this->setRow($row);
            unset($missing[$serial]);
        }

        // remove placeholders for unfetched rows
        foreach ($missing as $serial => $primaryValue) {
            unset($rows[$serial]);
        }

        return array_values($rows);
    }

    /**
     * @param mixed $spec
     *
     * @return string
     */
    public function getSerial(mixed $spec): string
    {
        $array = ($spec instanceof AbstractRow)
            ? $this->getSerialArrayFromRow($spec)
            : $this->getSerialArray($spec);

        $sep = "|\x1F"; // a pipe, and ASCII 31 ("unit separator")

        return $sep . implode($sep, $array) . $sep;
    }

    /**
     * @param AbstractRow $row
     *
     * @return AbstractRow
     * @throws RowAlreadyIdentityMappedException
     */
    public function memRow(AbstractRow $row): AbstractRow
    {
        $serial = $this->getSerial($row);
        $memory = $this->getRowBySerial($serial);

        if ($memory === null) {
            $this->setRow($row);

            return $row;
        }

        return $memory;
    }

    /**
     * @param AbstractRow $row
     *
     * @return void
     */
    public function setRow(AbstractRow $row): void
    {
        $serial = $this->getSerial($row);

        if (isset($this->memory[$serial])) {
            throw new RowAlreadyIdentityMappedException($row, $serial);
        }

        $this->memory[$serial] = $row;
    }

    /**
     * @param AbstractRow $row
     *
     * @return void
     * @throws UnexpectedTypeException
     */
    protected function assertRow(AbstractRow $row): void
    {
        $expect = $this->table::ROW_CLASS;
        if (!$row instanceof $expect) {
            throw new UnexpectedTypeException(
                'identity map row',
                $expect,
                get_class($row)
            );
        }
    }

    /**
     * @param       $value
     * @param mixed $column
     *
     * @return void
     * @throws PrimaryValueNotScalarException
     */
    protected function assertValueIsScalar($value, mixed $column): void
    {
        if (true !== is_scalar($value)) {
            throw new PrimaryValueNotScalarException(
                $column, $value
            );
        }
    }

    /**
     * @param string $serial
     *
     * @return AbstractRow|null
     */
    protected function getRowBySerial(string $serial): ?AbstractRow
    {
        return $this->memory[$serial] ?? null;
    }

    /**
     * @param mixed $primaryValue
     *
     * @return array
     */
    abstract protected function getSerialArray(mixed $primaryValue): array;

    /**
     * @param AbstractRow $row
     *
     * @return array
     */
    abstract protected function getSerialArrayFromRow(AbstractRow $row): array;
}
