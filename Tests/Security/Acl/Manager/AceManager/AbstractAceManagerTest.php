<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Tests\Security\Acl\Manager\AceManager;

use ProjectA\Bundle\AclBundle\Tests\Model\AbstractSecurityTest;
use ProjectA\Bundle\AclBundle\Tests\Model\User;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Role\Role;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class AbstractAceManagerTest extends AbstractSecurityTest
{
    public function testGrantSingleMaskForUser()
    {
        $user = new User();
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'test', $user->getRoles());
        $this->container->get('security.context')->setToken($token);

        $this->objectmanager->grant($this->object, MaskBuilder::MASK_EDIT, $user);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
        $this->assertTrue($this->manager->isGranted('EDIT', $this->object));
    }

    public function testGrantSingleMaskForRoleInterface()
    {
        $role = new Role('ROLE_USER');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_EDIT, $role);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
        $this->assertTrue($this->manager->isGranted('EDIT', $this->object));
    }

    public function testGrantSingleMaskForRoleString()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_EDIT, 'ROLE_USER');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
        $this->assertTrue($this->manager->isGranted('EDIT', $this->object));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGrantSingleMaskForInvalidSomething()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_EDIT, new \stdClass());
    }

    public function testRevokeForNonExistantAcl()
    {
        $this->objectmanager->revoke($this->object, MaskBuilder::MASK_MASTER, $this->token);
        $this->assertTrue(true);
    }

    public function testRevokeAllForIdentityForNonExistantAcl()
    {
        $this->objectmanager->revokeAllForIdentity($this->object, $this->token);
        $this->assertTrue(true);
    }

    public function testRevokeAllForNonExistantAcl()
    {
        $this->objectmanager->revokeAll($this->object);
        $this->assertTrue(true);
    }

    public function testPreloadDoesNotThrowException()
    {
        $this->objectmanager->preload(array($this->object));
        $this->assertTrue(true);
    }
}
