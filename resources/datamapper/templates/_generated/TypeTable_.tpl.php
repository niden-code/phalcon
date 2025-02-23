<?php

declare(strict_types=1);

namespace %namespace%\%type%\_generated;

use Phalcon\DataMapper\Table\AbstractTable;
use %namespace%\%type%\%type%AbstractRow;
use %namespace%\%type%\%type%AbstractTableSelect;

/**
 * @method ?%type%Row fetchRow(mixed $primaryVal)
 * @method %type%Row[] fetchRows(array $primaryVals)
 * @method %type%TableSelect select(array $whereEquals = [])
 * @method %type%Row newRow(array $cols = [])
 * @method %type%Row newSelectedRow(array $cols)
 */
abstract class Abstract%type%Table_ extends AbstractTable
{
}
