<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Tests\Security\Acl\Manager;

use ProjectA\Bundle\AclBundle\Tests\Model\AbstractSecurityTest;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class AclManagerTest extends AbstractSecurityTest
{
    public function testIfServiceIsCorrect()
    {
        $this->assertInstanceOf('\ProjectA\Bundle\AclBundle\Security\Acl\Manager\AclManager', $this->manager);
    }

    public function testIfGrantWorksForRoles()
    {
        $this->assertTrue($this->manager->isGranted('ROLE_USER'));
        $this->assertFalse($this->manager->isGranted('ROLE_ADMIN'));

        $adminToken = $this->createToken(array('ROLE_ADMIN'));
        $this->setToken($adminToken);

        $this->assertTrue($this->manager->isGranted('ROLE_USER'));
        $this->assertTrue($this->manager->isGranted('ROLE_ADMIN'));
    }
}
