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

use Phalcon\Container\Container;
use Phalcon\Container\Definition\Definitions;
use Phalcon\Container\Provider\ProviderInterface;

class ChildProvider implements ProviderInterface
{
    /**
     * @param Definitions $definitions
     *
     * @return void
     */
    public function provide(Definitions $definitions): void
    {
        $definitions->{ChildClass::class}->argument(0, 'one');
        $definitions->direct = 'ten';
        $definitions->lazy   = $definitions->call(
            function (Container $container) {
                return 'thirty';
            }
        );
    }
}
