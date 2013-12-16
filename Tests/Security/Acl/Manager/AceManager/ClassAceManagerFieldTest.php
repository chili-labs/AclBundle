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

class ClassAceManagerFieldTest extends AbstractSecurityTest
{
    public function testGrantSingleMask()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('VIEW', $this->object));
    }

    public function testGrantMultipleMask()
    {
        $maskBuilder = new MaskBuilder();
        $mask = $maskBuilder->add(MaskBuilder::MASK_VIEW)->add(MaskBuilder::MASK_DELETE);

        $this->classmanager->grant($this->object, $mask->get(), $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('EDIT', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('DELETE', $this->object, 'foo'));

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object));
        $this->assertFalse($this->manager->isGranted('DELETE', $this->object));
    }

    public function testOverwrite()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_EDIT, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('EDIT', $this->object, 'foo'));

        $this->classmanager->overwrite($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('EDIT', $this->object, 'foo'));
    }

    public function testRevoke()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->classmanager->revoke($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
    }

    public function testRevokeDifferentFields()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));

        $this->classmanager->revoke($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
    }

    public function testRevokeAllForIdentityForSingleGrant()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->classmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
    }

    public function testRevokeAllForIdentityDifferentFields()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));

        $this->classmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
    }

    public function testRevokeAllForIdentityForMultiGrant()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_MASTER, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_OWNER, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('OWNER', $this->object, 'foo'));

        $this->classmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $this->object, 'foo'));
    }

    public function testRevokeAllForIdentityForMultipleIdentities()
    {
        $token2 = $this->createToken();

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->classmanager->revokeAllForIdentity($this->object, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->container->get('security.context')->setToken($token2, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
    }

    public function testRevokeAllForSingleGrant()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->classmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
    }

    public function testRevokeAllDifferentFields()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));

        $this->classmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
    }

    public function testRevokeAllForMultiGrant()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_MASTER, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_OWNER, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('OWNER', $this->object, 'foo'));

        $this->classmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $this->object, 'foo'));
    }

    public function testRevokeAllForMultipleIdentities()
    {
        $token2 = $this->createToken();

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->classmanager->revokeAll($this->object, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->container->get('security.context')->setToken($token2);

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
    }
}
