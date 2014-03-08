<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Tests\Security\Acl\Manager\AceManager;

use ProjectA\Bundle\AclBundle\Tests\Model\AbstractSecurityTest;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class ObjectAceManagerFieldTest extends AbstractSecurityTest
{
    public function testGrantSingleMask()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('VIEW', $this->object));
    }

    public function testGrantMultipleMask()
    {
        $maskBuilder = new MaskBuilder();
        $mask = $maskBuilder->add(MaskBuilder::MASK_VIEW)->add(MaskBuilder::MASK_DELETE);

        $this->objectmanager->grant($this->object, $mask->get(), $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('EDIT', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('DELETE', $this->object, 'foo'));

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object));
        $this->assertFalse($this->manager->isGranted('DELETE', $this->object));
    }

    public function testRevoke()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->objectmanager->revoke($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
    }

    public function testRevokeDifferentFields()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));

        $this->objectmanager->revoke($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
    }

    public function testRevokeAllForIdentityForSingleGrant()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->objectmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
    }

    public function testRevokeAllForIdentityDifferentFields()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));

        $this->objectmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
    }

    public function testRevokeAllForIdentityForMultiGrant()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_MASTER, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_OWNER, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('OWNER', $this->object, 'foo'));

        $this->objectmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $this->object, 'foo'));
    }

    public function testRevokeAllForIdentityForMultipleIdentities()
    {
        $token2 = $this->createToken();

        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->objectmanager->revokeAllForIdentity($this->object, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->container->get('security.context')->setToken($token2, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
    }

    public function testRevokeAllForSingleGrant()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->objectmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
    }

    public function testRevokeAllDifferentFields()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));

        $this->objectmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
    }

    public function testRevokeAllForMultiGrant()
    {
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_MASTER, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_OWNER, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('OWNER', $this->object, 'foo'));

        $this->objectmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $this->object, 'foo'));
    }

    public function testRevokeAllForMultipleIdentities()
    {
        $token2 = $this->createToken();

        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->objectmanager->grant($this->object, MaskBuilder::MASK_VIEW, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->objectmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->container->get('security.context')->setToken($token2);

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
    }
}
