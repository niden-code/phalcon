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

use Phalcon\Container\Service\Collection;
use Phalcon\Container\Service\Provider;

class BenchServiceProvider implements Provider
{
    public function provide(Collection $services): void
    {
        $services->set(BenchService::class, BenchService::class);
    }
}
