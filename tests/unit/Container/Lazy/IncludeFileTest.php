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
use Phalcon\Container\Lazy\IncludeFile;

class IncludeFileTest extends LazyTestCase
{
    /**
     * @return void
     */
    public function testContainerLazyIncludeFileClosure(): void
    {
        $lazy   = new IncludeFile(
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
    public function testContainerLazyIncludeFileString(): void
    {
        $lazy   = new IncludeFile(
            $this->dataDir('fixtures/Container/include_file.php'),
        );
        $expected = 'included';
        $this->assertSame($expected, $this->actual($lazy));
    }
}
