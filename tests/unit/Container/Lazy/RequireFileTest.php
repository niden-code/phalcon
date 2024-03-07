<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Tests\Unit\Container\Lazy;

use Phalcon\Container\Lazy\Call;
use Phalcon\Container\Lazy\RequireFile;

class RequireFileTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyRequireFileCall(): void
    {
        $lazy   = new RequireFile(
            new Call(
                function ($container) {
                    return $this->dataDir('fixtures/Container/include_file.php');
                }
            )
        );
        $expected = 'included';
        $this->assertSame($expected, $this->actual($lazy));
    }

    /**
     * @return void
     */
    public function testContainerLazyRequireFileString(): void
    {
        $lazy   = new RequireFile(
            $this->dataDir('fixtures/Container/include_file.php'),
        );
        $expected = 'included';
        $this->assertSame($expected, $this->actual($lazy));
    }
}
