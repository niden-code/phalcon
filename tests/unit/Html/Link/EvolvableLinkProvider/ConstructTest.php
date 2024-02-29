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

namespace Phalcon\Tests\Unit\Html\Link\EvolvableLinkProvider;

use Phalcon\Html\Link\EvolvableLinkProvider;
use Phalcon\Html\Link\Interfaces\EvolvableLinkProviderInterface;
use PHPUnit\Framework\TestCase;

final class ConstructTest extends TestCase
{
    /**
     * Tests Phalcon\Html\Link\EvolvableLinkProvider :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testHtmlLinkEvolvableLinkConstruct(): void
    {
        $link = new EvolvableLinkProvider();

        $class = EvolvableLinkProviderInterface::class;
        $this->assertInstanceOf($class, $link);
    }
}
