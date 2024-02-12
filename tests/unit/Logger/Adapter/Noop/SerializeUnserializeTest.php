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

namespace Phalcon\Tests\Unit\Logger\Adapter\Noop;

use Phalcon\Logger\Adapter\Noop;
use Phalcon\Logger\Exception;
use PHPUnit\Framework\TestCase;
use UnitTester;

use function dataDir;
use function file_get_contents;
use function serialize;

final class SerializeUnserializeTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Noop :: serialize()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-09-03
     * @issue  15638
     */
    public function testLoggerAdapterNoopSerialize(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("This object cannot be serialized");

        $adapter = new Noop();

        $object = serialize($adapter);
    }

    /**
     * Tests Phalcon\Logger\Adapter\Noop :: unserialize
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-09-03
     * @issue  15638
     */
    public function testLoggerAdapterNoopUnserialize(): void
    {
        $serialized = file_get_contents(dataDir2('assets/logger/logger.serialized'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("This object cannot be unserialized");

        $object     = unserialize($serialized);
    }
}
