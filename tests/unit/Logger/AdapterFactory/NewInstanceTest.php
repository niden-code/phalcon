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

namespace Phalcon\Tests\Unit\Logger\AdapterFactory;

use Phalcon\Logger\Adapter\AdapterInterface;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\Exception;
use PHPUnit\Framework\TestCase;
use UnitTester;

use function logsDir2;
use function outputDir;
use function outputDir2;

final class NewInstanceTest extends TestCase
{
    /**
     * Tests Phalcon\Logger\AdapterFactory :: newInstance()
     *
     * @return void
     *
     * @throws Exception
     * @since  2020-09-09
     * @author Phalcon Team <team@phalcon.io>
     */
    public function testLoggerAdapterFactoryNewInstance(): void
    {
        $fileName = getNewFileName2();
        $fileName = logsDir2($fileName);
        $factory  = new AdapterFactory();

        $logger = $factory->newInstance('stream', $fileName);
        $this->assertInstanceOf(AdapterInterface::class, $logger);
    }

    /**
     * Tests Phalcon\Logger\AdapterFactory :: newInstance() - exception
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-06
     */
    public function testLoggerAdapterFactoryNewInstanceException(): void
    {
        $factory = new AdapterFactory();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Service unknown is not registered');

        $logger = $factory->newInstance('unknown', '123.log');
    }
}
