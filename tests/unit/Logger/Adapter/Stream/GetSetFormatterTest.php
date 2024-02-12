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
use Phalcon\Logger\Formatter\FormatterInterface;
use Phalcon\Logger\Formatter\Line;
use PHPUnit\Framework\TestCase;
use UnitTester;

final class GetSetFormatterTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Stream :: getFormatter()/setFormatter()
     */
    public function testLoggerAdapterStreamGetSetFormatter(): void
    {
        $fileName = getNewFileName2('log');
        $fileName = logsDir2($fileName);

        $adapter = new Stream($fileName);

        $adapter->setFormatter(new Line());
        $actual = $adapter->getFormatter();

        $this->assertInstanceOf(FormatterInterface::class, $actual);

        $adapter->close();
        safeDeleteFile2($fileName);
    }
}
