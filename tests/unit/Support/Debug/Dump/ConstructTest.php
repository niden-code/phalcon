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

namespace Phalcon\Tests\Unit\Support\Debug\Dump;

use Phalcon\Support\Debug\Dump;
use Phalcon\Tests\Fixtures\Support\Dump\ClassProperties;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function strip_tags;
use function trim;

use const PHP_OS_FAMILY;

final class ConstructTest extends TestCase
{
    /**
     * Tests Phalcon\Support\Debug\Dump :: __construct() - dump properties
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/13315
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportDebugDumpConstructDump(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $class = new ClassProperties();
        $dump  = new Dump([], true);

        $actual = callProtectedMethod($dump, 'output', $class);

        $expected = file_get_contents(
            dataDir('fixtures/Support/Dump/class_properties.txt')
        );

        // Test without HTML
        $actual = strip_tags($actual);

        // Remove a trailing newline
        $expected = trim($expected);
        $this->assertSame($expected, $actual);
    }
}
