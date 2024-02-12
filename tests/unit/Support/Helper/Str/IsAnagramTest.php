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

namespace Phalcon\Tests\Unit\Support\Helper\Str;

use Phalcon\Support\Helper\Str\IsAnagram;
use PHPUnit\Framework\TestCase;

final class IsAnagramTest extends TestCase
{
    /**
     * Tests Phalcon\Support\Helper\Str :: isAnagram()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrIsAnagram(): void
    {
        $object = new IsAnagram();
        $actual = $object('rail safety', 'fairy tales');
        $this->assertTrue($actual);
    }
}
