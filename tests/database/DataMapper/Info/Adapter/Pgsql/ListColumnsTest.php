<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Database\DataMapper\Info\Adapter\Pgsql;

use Phalcon\DataMapper\Info\Adapter\Pgsql;
use Phalcon\DataMapper\Pdo\Exception\Exception;
use Phalcon\Tests\AbstractDatabaseTestCase;

final class ListColumnsTest extends AbstractDatabaseTestCase
{
    /**
     * @return void
     * @throws Exception
     * @since  2025-01-14
     *
     * @group  pgsql
     *
     */
    public function testDmInfoAdapterPgsqlListTables(): void
    {
        $connection = self::getDataMapperConnection();

        $pgsql  = new Pgsql($connection);
        $schema = $pgsql->getCurrentSchema();

        $expected = [
            'field_primary'           => [
                'afterField'      => null,
                'comment'         => 'field_primary field',
                'default'         => 0,
                'hasDefault'      => true,
                'isAutoIncrement' => true,
                'isFirst'         => true,
                'isNotNull'       => true,
                'isNumeric'       => true,
                'isPrimary'       => true,
                'isUnsigned'      => null,
                'name'            => 'field_primary',
                'options'         => null,
                'scale'           => 0,
                'size'            => 32,
                'type'            => 'integer',
            ],
            'field_blob'              => [
                'afterField'      => 'field_primary',
                'comment'         => 'field_blob field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_blob',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'bytea',
            ],
            'field_binary'            => [
                'afterField'      => 'field_blob',
                'comment'         => 'field_binary field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_binary',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'bytea',
            ],
            'field_bit'               => [
                'afterField'      => 'field_binary',
                'comment'         => 'field_bit field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_bit',
                'options'         => null,
                'scale'           => null,
                'size'            => 10,
                'type'            => 'bit',
            ],
            'field_bit_default'       => [
                'afterField'      => 'field_bit',
                'comment'         => 'field_bit_default field',
                'default'         => '1',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_bit_default',
                'options'         => null,
                'scale'           => null,
                'size'            => 10,
                'type'            => 'bit',
            ],
            'field_bigint'            => [
                'afterField'      => 'field_bit_default',
                'comment'         => 'field_bigint field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_bigint',
                'options'         => null,
                'scale'           => 0,
                'size'            => 64,
                'type'            => 'bigint',
            ],
            'field_bigint_default'    => [
                'afterField'      => 'field_bigint',
                'comment'         => 'field_bigint_default field',
                'default'         => 1,
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_bigint_default',
                'options'         => null,
                'scale'           => 0,
                'size'            => 64,
                'type'            => 'bigint',
            ],
            'field_boolean'           => [
                'afterField'      => 'field_bigint_default',
                'comment'         => 'field_boolean field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_boolean',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'boolean',
            ],
            'field_boolean_default'   => [
                'afterField'      => 'field_boolean',
                'comment'         => 'field_boolean_default field',
                'default'         => 'true',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_boolean_default',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'boolean',
            ],
            'field_char'              => [
                'afterField'      => 'field_boolean_default',
                'comment'         => 'field_char field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_char',
                'options'         => null,
                'scale'           => null,
                'size'            => 10,
                'type'            => 'character',
            ],
            'field_char_default'      => [
                'afterField'      => 'field_char',
                'comment'         => 'field_char_default field',
                'default'         => 'ABC',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_char_default',
                'options'         => null,
                'scale'           => null,
                'size'            => 10,
                'type'            => 'character',
            ],
            'field_decimal'           => [
                'afterField'      => 'field_char_default',
                'comment'         => 'field_decimal field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_decimal',
                'options'         => null,
                'scale'           => 4,
                'size'            => 10,
                'type'            => 'numeric',
            ],
            'field_decimal_default'   => [
                'afterField'      => 'field_decimal',
                'comment'         => 'field_decimal_default field',
                'default'         => 14.5678,
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_decimal_default',
                'options'         => null,
                'scale'           => 4,
                'size'            => 10,
                'type'            => 'numeric',
            ],
            'field_enum'              => [
                'afterField'      => 'field_decimal_default',
                'comment'         => 'field_enum field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_enum',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'text',
            ],
            'field_integer'           => [
                'afterField'      => 'field_enum',
                'comment'         => 'field_integer field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_integer',
                'options'         => null,
                'scale'           => 0,
                'size'            => 32,
                'type'            => 'integer',
            ],
            'field_integer_default'   => [
                'afterField'      => 'field_integer',
                'comment'         => 'field_integer_default field',
                'default'         => 1,
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_integer_default',
                'options'         => null,
                'scale'           => 0,
                'size'            => 32,
                'type'            => 'integer',
            ],
            'field_json'              => [
                'afterField'      => 'field_integer_default',
                'comment'         => 'field_json field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_json',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'json',
            ],
            'field_float'             => [
                'afterField'      => 'field_json',
                'comment'         => 'field_float field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_float',
                'options'         => null,
                'scale'           => null,
                'size'            => 24,
                'type'            => 'real',
            ],
            'field_float_default'     => [
                'afterField'      => 'field_float',
                'comment'         => 'field_float_default field',
                'default'         => 14.5678,
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_float_default',
                'options'         => null,
                'scale'           => null,
                'size'            => 24,
                'type'            => 'real',
            ],
            'field_date'              => [
                'afterField'      => 'field_float_default',
                'comment'         => 'field_date field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_date',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'date',
            ],
            'field_date_default'      => [
                'afterField'      => 'field_date',
                'comment'         => 'field_date_default field',
                'default'         => '2018-10-01',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_date_default',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'date',
            ],
            'field_datetime'          => [
                'afterField'      => 'field_date_default',
                'comment'         => 'field_datetime field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_datetime',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'timestamp without time zone',
            ],
            'field_datetime_default'  => [
                'afterField'      => 'field_datetime',
                'comment'         => 'field_datetime_default field',
                'default'         => '2018-10-01 12:34:56',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_datetime_default',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'timestamp without time zone',
            ],
            'field_time'              => [
                'afterField'      => 'field_datetime_default',
                'comment'         => 'field_time field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_time',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'time without time zone',
            ],
            'field_time_default'      => [
                'afterField'      => 'field_time',
                'comment'         => 'field_time_default field',
                'default'         => '12:34:56',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_time_default',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'time without time zone',
            ],
            'field_timestamp'         => [
                'afterField'      => 'field_time_default',
                'comment'         => 'field_timestamp field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_timestamp',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'timestamp without time zone',
            ],
            'field_timestamp_default' => [
                'afterField'      => 'field_timestamp',
                'comment'         => 'field_timestamp_default field',
                'default'         => '2018-10-01 12:34:56',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_timestamp_default',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'timestamp without time zone',
            ],
            'field_timestamp_current' => [
                'afterField'      => 'field_timestamp_default',
                'comment'         => 'field_timestamp_current field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_timestamp_current',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'timestamp without time zone',
            ],
            'field_mediumint'         => [
                'afterField'      => 'field_timestamp_current',
                'comment'         => 'field_mediumint field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_mediumint',
                'options'         => null,
                'scale'           => 0,
                'size'            => 32,
                'type'            => 'integer',
            ],
            'field_mediumint_default' => [
                'afterField'      => 'field_mediumint',
                'comment'         => 'field_mediumint_default field',
                'default'         => 1,
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_mediumint_default',
                'options'         => null,
                'scale'           => 0,
                'size'            => 32,
                'type'            => 'integer',
            ],
            'field_smallint'          => [
                'afterField'      => 'field_mediumint_default',
                'comment'         => 'field_smallint field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_smallint',
                'options'         => null,
                'scale'           => 0,
                'size'            => 16,
                'type'            => 'smallint',
            ],
            'field_smallint_default'  => [
                'afterField'      => 'field_smallint',
                'comment'         => 'field_smallint_default field',
                'default'         => 1,
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_smallint_default',
                'options'         => null,
                'scale'           => 0,
                'size'            => 16,
                'type'            => 'smallint',
            ],
            'field_tinyint'           => [
                'afterField'      => 'field_smallint_default',
                'comment'         => 'field_tinyint field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_tinyint',
                'options'         => null,
                'scale'           => 0,
                'size'            => 16,
                'type'            => 'smallint',
            ],
            'field_tinyint_default'   => [
                'afterField'      => 'field_tinyint',
                'comment'         => 'field_tinyint_default field',
                'default'         => 1,
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => true,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_tinyint_default',
                'options'         => null,
                'scale'           => 0,
                'size'            => 16,
                'type'            => 'smallint',
            ],
            'field_longtext'          => [
                'afterField'      => 'field_tinyint_default',
                'comment'         => 'field_longtext field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_longtext',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'text',
            ],
            'field_mediumtext'        => [
                'afterField'      => 'field_longtext',
                'comment'         => 'field_mediumtext field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_mediumtext',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'text',
            ],
            'field_tinytext'          => [
                'afterField'      => 'field_mediumtext',
                'comment'         => 'field_tinytext field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_tinytext',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'text',
            ],
            'field_text'              => [
                'afterField'      => 'field_tinytext',
                'comment'         => 'field_text field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_text',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'text',
            ],
            'field_varbinary'         => [
                'afterField'      => 'field_text',
                'comment'         => 'field_varbinary field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_varbinary',
                'options'         => null,
                'scale'           => null,
                'size'            => null,
                'type'            => 'bytea',
            ],
            'field_varchar'           => [
                'afterField'      => 'field_varbinary',
                'comment'         => 'field_varchar field',
                'default'         => null,
                'hasDefault'      => false,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_varchar',
                'options'         => null,
                'scale'           => null,
                'size'            => 10,
                'type'            => 'character varying',
            ],
            'field_varchar_default'   => [
                'afterField'      => 'field_varchar',
                'comment'         => 'field_varchar_default field',
                'default'         => 'D',
                'hasDefault'      => true,
                'isAutoIncrement' => false,
                'isFirst'         => false,
                'isNotNull'       => false,
                'isNumeric'       => false,
                'isPrimary'       => false,
                'isUnsigned'      => null,
                'name'            => 'field_varchar_default',
                'options'         => null,
                'scale'           => null,
                'size'            => 10,
                'type'            => 'character varying',
            ],
        ];
        $actual = $pgsql->listColumns($schema, 'co_dialect');

        $this->assertSame($expected, $actual);
    }
}
