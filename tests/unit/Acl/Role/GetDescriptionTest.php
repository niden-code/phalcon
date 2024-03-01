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

namespace Phalcon\Tests\Unit\Acl\Role;

use Phalcon\Acl\Role;
use Phalcon\Tests\Support\AbstractUnitTestCase;

final class GetDescriptionTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Acl\Role :: getDescription()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclRoleGetDescription(): void
    {
        $name        = 'Administrator';
        $description = 'The admin unit';
        $role        = new Role($name, $description);

        $expected = $description;
        $actual   = $role->getDescription();
        $this->assertSame($expected, $actual);
    }

    /**
     * Tests Phalcon\Acl\Role :: getDescription() - empty
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclRoleGetDescriptionEmpty(): void
    {
        $name = 'Administrator';
        $role = new Role($name);

        $actual = $role->getDescription();
        $this->assertEmpty($actual);
    }
}
