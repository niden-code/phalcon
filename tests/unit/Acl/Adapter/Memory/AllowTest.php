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

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Component;
use Phalcon\Acl\Enum;
use Phalcon\Acl\Exception;
use Phalcon\Acl\Exception as AclException;
use Phalcon\Acl\Role;
use Phalcon\Tests\Fixtures\Acl\TestComponentAware;
use Phalcon\Tests\Fixtures\Acl\TestRoleAware;
use PHPUnit\Framework\TestCase;
use RuntimeException;

use function restore_error_handler;
use function set_error_handler;

use const E_USER_WARNING;

final class AllowTest extends TestCase
{
    /**
     * Tests Phalcon\Acl\Adapter\Memory :: allow()
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testAclAdapterMemoryAllow(): void
    {
        $acl = new Memory();

        $acl->setDefaultAction(Enum::DENY);
        $acl->addRole('Guests');
        $acl->addRole('Member');

        $acl->addComponent('Post', ['update']);
        $acl->allow('Member', 'Post', 'update');

        $actual = $acl->isAllowed('Guest', 'Post', 'update');
        $this->assertFalse($actual);

        $actual = $acl->isAllowed('Guest', 'Post', 'update');
        $this->assertFalse($actual);

        $actual = $acl->isAllowed('Member', 'Post', 'update');
        $this->assertTrue($actual);
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: allow() - wildcard
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-06-16
     */
    public function testAclAdapterMemoryAllowWildcard(): void
    {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);
        $acl->addRole('Member');
        $acl->addComponent('Post', ['update']);

        $acl->allow('Member', 'Post', '*');

        $actual = $acl->isAllowed('Member', 'Post', 'update');
        $this->assertTrue($actual);

        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);
        $acl->addRole('Member');
        $acl->addComponent('Post', ['update']);

        $acl->allow('Member', '*', '*');
        $actual = $acl->isAllowed('Member', 'Post', 'update');
        $this->assertTrue($actual);

        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);
        $acl->addRole('Member');
        $acl->addRole('Guest');
        $acl->addInherit('Guest', 'Member');
        $acl->addComponent('Post', ['update']);

        $acl->allow('Member', '*', '*');
        $actual = $acl->isAllowed('Guest', 'Post', 'update');
        $this->assertTrue($actual);

        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);

        $aclRoles = [
            'Admin'  => new Role('Admin'),
            'Users'  => new Role('Users'),
            'Guests' => new Role('Guests'),
        ];

        $aclComponents = [
            'welcome' => ['index', 'about'],
            'account' => ['index'],
        ];

        foreach ($aclRoles as $Role => $object) {
            $acl->addRole($object);
        }

        foreach ($aclComponents as $component => $actions) {
            $acl->addComponent(new Component($component), $actions);
        }
        $acl->allow('*', 'welcome', 'index');

        foreach ($aclRoles as $Role => $object) {
            $actual = $acl->isAllowed($Role, 'welcome', 'index');
            $this->assertTrue($actual);
        }

        $acl->deny('*', 'welcome', 'index');
        foreach ($aclRoles as $Role => $object) {
            $actual = $acl->isAllowed($Role, 'welcome', 'index');
            $this->assertFalse($actual);
        }
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: allow() - exception
     *
     * @dataProvider providerExceptions
     *
     * @return void
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-06-16
     */
    public function testAclAdapterMemoryAllowException(
        string $message,
        string $role,
        string $component,
        array|string $access
    ): void {
        $acl = new Memory();
        $acl->setDefaultAction(Enum::DENY);
        $acl->addRole('Member');
        $acl->addComponent('Post', ['update']);

        $this->expectException(AclException::class);
        $this->expectExceptionMessage($message);

        $acl->allow($role, $component, $access);
    }

    /**
     * Tests Phalcon\Acl\Adapter\Memory :: allow() - function
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/11235
     *
     * @return void
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2015-12-16
     */
    public function testAclAdapterMemoryAllowFunction(): void
    {
        $acl = new Memory();

        $acl->setDefaultAction(Enum::DENY);

        $acl->addRole('Guests');
        $acl->addRole('Members', 'Guests');
        $acl->addRole('Admins', 'Members');

        $acl->addComponent('Post', ['update']);

        $guest         = new TestRoleAware(1, 'Guests');
        $member        = new TestRoleAware(2, 'Members');
        $anotherMember = new TestRoleAware(3, 'Members');
        $admin         = new TestRoleAware(4, 'Admins');
        $model         = new TestComponentAware(2, 'Post');

        $acl->deny('Guests', 'Post', 'update');

        $acl->allow(
            'Members',
            'Post',
            'update',
            function (TestRoleAware $user, TestComponentAware $model) {
                return $user->getId() == $model->getUser();
            }
        );

        $acl->allow('Admins', 'Post', 'update');

        $actual = $acl->isAllowed($guest, $model, 'update');
        $this->assertFalse($actual);

        $actual = $acl->isAllowed($member, $model, 'update');
        $this->assertTrue($actual);

        $actual = $acl->isAllowed($anotherMember, $model, 'update');
        $this->assertFalse($actual);

        $actual = $acl->isAllowed($admin, $model, 'update');
        $this->assertTrue($actual);
    }

    /**
     * @return array
     */
    public static function providerExceptions(): array
    {
        return [
            [
                "Role 'Unknown' does not exist in the ACL",
                'Unknown',
                'Post',
                'update'
            ],
            [
                "Component 'Unknown' does not exist in the ACL",
                'Member',
                'Unknown',
                'update'
            ],
            [
                "Access 'Unknown' does not exist in component 'Post'",
                'Member',
                'Post',
                'Unknown'
            ],
            [
                "Access 'Unknown' does not exist in component 'Post'",
                'Member',
                'Post',
                ['Unknown']
            ],
        ];
    }
}
