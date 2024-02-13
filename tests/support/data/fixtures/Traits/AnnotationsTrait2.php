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

namespace Phalcon\Tests1\Fixtures\Traits;

use Phalcon\Annotations\Adapter\Apcu;
use Phalcon\Annotations\Adapter\Memory;
use Phalcon\Annotations\Adapter\Stream;

use function outputDir;
use function outputDir2;

trait AnnotationsTrait2
{
    public static function providerExamples(): array
    {
        return [
            [
                Apcu::class,
                [
                    'prefix'   => 'nova_prefix',
                    'lifetime' => 3600,
                ],
            ],
            [
                Memory::class,
                [],
            ],
            [
                Stream::class,
                [
                    'annotationsDir' => outputDir2('annotations/'),
                ],
            ],
        ];
    }
}
