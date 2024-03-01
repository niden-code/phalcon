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

namespace Phalcon\Tests\Unit\Assets\Collection;

use Phalcon\Assets\Collection;
use Phalcon\Tests\Support\AbstractUnitTestCase;

use function dataDir2;

/**
 * Class GetSetSourcePathTest extends AbstractUnitTestCase
 *
 * @package Phalcon\Tests\Unit\Assets\Collection
 */
final class GetSetSourcePathTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Assets\Collection :: getSourcePath() / setSourcePath()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsCollectionGetSetSourcePath(): void
    {
        $collection = new Collection();
        $sourcePath = self::dataDir('assets');
        $collection->setSourcePath($sourcePath);

        $this->assertSame($sourcePath, $collection->getSourcePath());
    }
}
