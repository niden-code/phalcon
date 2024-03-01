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

namespace Phalcon\Tests\Unit\Assets\Asset;

use Phalcon\Tests\Support\AbstractUnitTestCase;

abstract class AbstractAssetCase extends AbstractUnitTestCase
{
    /**
     * @return array[]
     */
    public static function providerCssExamples(): array
    {
        return [
            [
                'css/docs.css',
                true,
                true,
            ],
            [
                'https://phalcon.ld/css/docs.css',
                false,
                true,
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function providerExamples(): array
    {
        return [
            [
                'css',
                'css/docs.css',
            ],
            [
                'js',
                'js/jquery.js',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function providerJsExamples(): array
    {
        return [
            [
                'js/jquery.js',
                true,
                true,
            ],
            [
                'https://phalcon.ld/js/jquery.js',
                false,
                true,
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function providerLocal(): array
    {
        return [
            [
                'css',
                'css/docs.css',
            ],
            [
                'js',
                'js/jquery.js',
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function providerPath(): array
    {
        return [
            [
                'css',
                'css/docs.css',
                true,
            ],
            [
                'js',
                'js/jquery.js',
                true,
            ],
            [
                'css',
                'https://phalcon.ld/css/docs.css',
                false,
            ],
            [
                'js',
                'https://phalcon.ld/js/jquery.js',
                false,
            ],
        ];
    }

    /**
     * @return string[][]
     */
    public static function providerRemote(): array
    {
        return [
            [
                'css',
                'https://phalcon.ld/css/docs.css',
            ],
            [
                'js',
                'https://phalcon.ld/js/jquery.js',
            ],
        ];
    }
}
