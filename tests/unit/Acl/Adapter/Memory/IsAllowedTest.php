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

namespace Phalcon\Tests\Unit\Acl\Adapter\Memory;

use Codeception\Stub;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Component;
use Phalcon\Acl\Enum;
use Phalcon\Acl\Exception;
use Phalcon\Acl\Exception as AclException;
use Phalcon\Acl\Role;
use Phalcon\Tests\Fixtures\Acl\TestComponentAware;
use Phalcon\Tests\Fixtures\Acl\TestRoleAware;
use Phalcon\Tests\Fixtures\Acl\TestRoleComponentAware;
use RuntimeException;
use stdClass;
use PHPUnit\Framework\TestCase;

use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function restore_error_handler;

use const E_USER_WARNING;

final class IsAllowedTest extends TestCase
{
    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - default
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/12573
     *
     * @return void
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2017-01-25
     */
    public function testAclAdapterMemoryIsAllowedDefault(): void
    {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);

        $acl->addComponent(
            new Component('Post'),
            [
                'index',
                'update',
                'create',
            ]
        );
        $acl->addRole(new Role('Guests'));
        $acl->allow('Guests', 'Post', 'index');

        $actual = $acl->isAllowed('Guests', 'Post', 'index');
        $this->assertTrue($actual);

        $actual = $acl->isAllowed('Guests', 'Post', 'update');
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - objects
     *
     * @return void
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2017-02-15
     */
    public function testAclAdapterMemoryIsAllowedObjects(): void
    {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);

        $role      = new Role('Guests');
        $component = new Component('Post');

        $acl->addRole($role);
        $acl->addComponent(
            $component,
            [
                'index',
                'update',
                'create',
            ]
        );
        $acl->allow('Guests', 'Post', 'index');

        $actual = $acl->isAllowed($role, $component, 'index');
        $this->assertTrue($actual);

        $actual = $acl->isAllowed($role, $component, 'update');
        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - same class
     *
     * @return void
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2017-02-15
     */
    public function testAclAdapterMemoryIsAllowedSameClass(): void
    {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);

        $role      = new TestRoleComponentAware(1, 'User', 'Admin');
        $component = new TestRoleComponentAware(2, 'User', 'Admin');

        $acl->addRole('Admin');
        $acl->addComponent('User', ['update']);
        $acl->allow(
            'Admin',
            'User',
            ['update'],
            function (
                TestRoleComponentAware $admin,
                TestRoleComponentAware $user
            ) {
                return $admin->getUser() == $user->getUser();
            }
        );

        $actual = $acl->isAllowed($role, $component, 'update');
        $this->assertFalse($actual);

        $actual = $acl->isAllowed($role, $role, 'update');
        $this->assertTrue($actual);

        $actual = $acl->isAllowed($component, $component, 'update');
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - function no parameters
     *
     * @return void
     *
     * @author  Phalcon Team <team@phalcon.io>
     * @since   2019-06-16
     */
    public function testAclAdapterMemoryIsAllowedFunctionNoParameters(): void
    {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);

        $acl->addRole('Admin');
        $acl->addComponent('User', ['update']);
        $acl->allow(
            'Admin',
            'User',
            ['update'],
            function () {
                return true;
            }
        );

        $actual = $acl->isAllowed('Admin', 'User', 'update');
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - function not enough
     * parameters
     *
     * @return void
     *
     * @author  Phalcon Team <team@phalcon.io>
     * @since   2019-06-16
     */
    public function testAclAdapterMemoryIsAllowedFunctionNotEnoughParameters(): void
    {
        $acl = new Memory();

        $acl->setDefaultAction(Enum::ALLOW);
        $acl->setNoArgumentsDefaultAction(Enum::DENY);

        $acl->addRole('Members');
        $acl->addComponent('Post', ['update']);

        $member = new TestRoleAware(2, 'Members');
        $model  = new TestComponentAware(2, 'Post');

        $acl->allow(
            'Members',
            'Post',
            'update',
            function ($parameter, $value) {
                return $parameter % $value == 0;
            }
        );

        $this->expectException(AclException::class);
        $this->expectExceptionMessage(
            "You did not provide all necessary parameters for the " .
            "defined function when checking if 'Members' can 'update' for 'Post'."
        );

        $acl->isAllowed(
            $member,
            $model,
            'update',
            [
                'parameter' => 1,
                'one'       => 2,
            ]
        );
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - RoleInterface exception
     *
     * @return void
     *
     * @author  Phalcon Team <team@phalcon.io>
     * @since   2019-06-16
     */
    public function testAclAdapterMemoryIsAllowedRoleInterfaceException(): void
    {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);
        $acl->addRole('Member');
        $acl->addComponent('Post', ['update']);
        $acl->allow('Member', 'Post', 'update');

        $this->expectException(AclException::class);
        $this->expectExceptionMessage(
            'Object passed as roleName must implement ' .
            'Phalcon\Acl\RoleAwareInterface or Phalcon\Acl\RoleInterface'
        );

        $acl->isAllowed(new stdClass(), 'Post', 'update');
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - ComponentInterface exception
     *
     * @return void
     *
     * @author  Phalcon Team <team@phalcon.io>
     * @since   2019-06-16
     */
    public function testAclAdapterMemoryIsAllowedComponentInterfaceException(): void
    {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);
        $acl->addRole('Member');
        $acl->addComponent('Post', ['update']);
        $acl->allow('Member', 'Post', 'update');

        $this->expectException(AclException::class);
        $this->expectExceptionMessage(
            'Object passed as componentName must implement ' .
            'Phalcon\Acl\ComponentAwareInterface or Phalcon\Acl\ComponentInterface'
        );

        $acl->isAllowed('Member', new stdClass(), 'update');
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - fireEvent returns false
     *
     * @return void
     *
     * @author  Phalcon Team <team@phalcon.io>
     * @since   2021-09-27
     */
    public function testAclAdapterMemoryIsAllowedFireEventFalse(): void
    {
        $acl = Stub::make(
            Memory::class,
            [
                'fireManagerEvent' => false,
            ]
        );

        $acl->addRole('Member');
        $acl->addComponent('Post', ['update']);
        $acl->allow('Member', 'Post', 'update');
        $actual = $acl->isAllowed('Member', 'Post', 'update');

        $this->assertFalse($actual);
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: isAllowed() - documentation example
     *
     * @return void
     *
     * @author  Phalcon Team <team@phalcon.io>
     * @since   2022-12-09
     */
    public function testAclAdapterMemoryIsAllowedDocumentationExample(): void
    {
        $acl = new Memory();

        /**
         * Setup the ACL
         */
        $acl->addRole('manager');
        $acl->addRole('accounting');
        $acl->addRole('guest');

        $acl->addComponent(
            'admin',
            [
                'dashboard',
                'users',
                'view',
            ]
        );
        $acl->addComponent(
            'reports',
            [
                'list',
                'add',
                'view',
            ]
        );
        $acl->addComponent(
            'session',
            [
                'login',
                'logout',
            ]
        );

        $acl->allow('manager', 'admin', 'dashboard');
        $acl->allow('manager', 'reports', ['list', 'add']);
        $acl->allow('accounting', 'reports', '*');
        $acl->allow('*', 'session', '*');

        // true - defined explicitly
        $actual = $acl->isAllowed('manager', 'admin', 'dashboard');
        $this->assertTrue($actual);

        // true - defined with wildcard
        $actual = $acl->isAllowed('manager', 'session', 'login');
        $this->assertTrue($actual);

        // true - defined with wildcard
        $actual = $acl->isAllowed('accounting', 'reports', 'view');
        $this->assertTrue($actual);

        // false - defined explicitly
        $actual = $acl->isAllowed('guest', 'reports', 'view');
        $this->assertFalse($actual);

        // false - default access level
        $actual = $acl->isAllowed('guest', 'reports', 'add');
        $this->assertFalse($actual);
    }
}
