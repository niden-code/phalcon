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

namespace Phalcon\Tests\Unit\Logger\Adapter\Stream;

use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Exception;
use PHPUnit\Framework\TestCase;
use UnitTester;

use function serialize;

final class SerializeUnserializeTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Stream :: serialize()/unserialize
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-09-03
     * @issue  15638
     */
    public function testLoggerAdapterStreamSerializeUnserialize(): void
    {
        $fileName = getNewFileName2('log');
        $outputPath = logsDir2();
        $adapter = new Stream($outputPath . $fileName);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("This object cannot be serialized");

        $object = serialize($adapter);
    }
}
