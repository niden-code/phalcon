<?php

/**
 * This file is part of the Phalcon.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Support\Helper\Str;

use function array_merge;
use function end;
use function implode;
use function str_ends_with;
use function str_starts_with;
use function trim;

/**
 * Concatenates strings using the separator only once without duplication in
 * places concatenation
 */
class Concat
{
    /**
     * @param string $delimiter
     * @param string $first
     * @param string $second
     * @param string ...$arguments
     *
     * @return string
     */
    public function __invoke(
        string $delimiter,
        string $first,
        string $second,
        string ...$arguments
    ): string {
        $data       = [];
        $parameters = array_merge([$first, $second], $arguments);
        $last       = end($parameters) ?? $second;

        $prefix = str_starts_with($first, $delimiter) ? $delimiter : '';
        $suffix = str_ends_with($last, $delimiter) ? $delimiter : '';

        foreach ($parameters as $parameter) {
            $data[] = trim($parameter, $delimiter);
        }

        return $prefix . implode($delimiter, $data) . $suffix;
    }
}
