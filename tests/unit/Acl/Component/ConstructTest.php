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

namespace Phalcon\Tests\Unit\Acl\Component;

use Phalcon\Acl\Component;
use Phalcon\Acl\Exception;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Acl\Component :: __construct()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclComponentConstruct(): void
    {
        $name      = 'Customer';
        $component = new Component($name);

        $this->assertInstanceOf(Component::class, $component);
    }

    /**
     * Tests Phalcon\Acl\Component :: __construct() - wildcard
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclComponentConstructWithWildcardThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Component name cannot be '*'");

        $component = new Component('*');
    }
}
