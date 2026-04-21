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

namespace Phalcon\Tests\Benchmarks\Container\Fake;

class BenchService
{
    public string $value = 'bench';

    public function getValue(): string
    {
        return $this->value;
    }

    public static function staticValue(): string
    {
        return 'static-bench';
    }
}
