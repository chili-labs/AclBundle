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
use ProjectA\Bundle\AclBundle\Tests\Model\SomeObject;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class ClassAceManagerFieldTest extends AbstractSecurityTest
{
    public function testGrantSingleMask()
    {
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('VIEW', $this->object));

        $otherObject = new SomeObject(1000);

        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertFalse($this->manager->isGranted('VIEW', $otherObject));
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

        $otherObject = new SomeObject(1000);

        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertFalse($this->manager->isGranted('EDIT', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('DELETE', $otherObject, 'foo'));

        $this->assertFalse($this->manager->isGranted('VIEW', $otherObject));
        $this->assertFalse($this->manager->isGranted('DELETE', $otherObject));
    }

    public function testGrantMultipleObjects()
    {
        $maskBuilder = new MaskBuilder();
        $mask = $maskBuilder->add(MaskBuilder::MASK_VIEW)->add(MaskBuilder::MASK_DELETE);

        $this->classmanager->grant($this->object, $mask->get(), $this->token);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
        $this->assertFalse($this->manager->isGranted('EDIT', $this->object));
        $this->assertTrue($this->manager->isGranted('DELETE', $this->object));

        $otherObject = new SomeObject(1000);

        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject));
        $this->assertFalse($this->manager->isGranted('EDIT', $otherObject));
        $this->assertTrue($this->manager->isGranted('DELETE', $otherObject));
    }

    public function testRevoke()
    {
        $otherObject = new SomeObject(1000);

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'foo'));

        $this->classmanager->revoke($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
        $this->assertFalse($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject));
    }

    public function testRevokeDifferentFields()
    {
        $otherObject = new SomeObject(1000);

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'bar'));

        $this->classmanager->revoke($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
        $this->assertFalse($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'bar'));
    }

    public function testRevokeAllForIdentityForSingleGrant()
    {
        $otherObject = new SomeObject(1000);

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token);

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'foo'));

        $this->classmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object));
        $this->assertFalse($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject));
    }

    public function testRevokeAllForIdentityDifferentFields()
    {
        $otherObject = new SomeObject(1000);

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'bar'));

        $this->classmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'bar'));
        $this->assertFalse($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'bar'));
    }

    public function testRevokeAllForIdentityForMultiGrant()
    {
        $otherObject = new SomeObject(1000);

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_MASTER, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_OWNER, $this->token, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('OWNER', $this->object, 'foo'));
        $this->assertTrue($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('MASTER', $otherObject, 'foo'));
        $this->assertTrue($this->manager->isGranted('OWNER', $otherObject, 'foo'));

        $this->classmanager->revokeAllForIdentity($this->object, $this->token, 'foo');

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('MASTER', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $this->object, 'foo'));
        $this->assertFalse($this->manager->isGranted('VIEW', $otherObject, 'foo'));
        $this->assertFalse($this->manager->isGranted('MASTER', $otherObject, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $otherObject, 'foo'));
    }

    public function testRevokeAllForIdentityForMultipleIdentities()
    {
        $token2 = $this->createToken();

        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $this->token, 'foo');
        $this->classmanager->grant($this->object, MaskBuilder::MASK_VIEW, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->classmanager->revokeAllForIdentity($this->object, $token2, 'foo');

        $this->assertTrue($this->manager->isGranted('VIEW', $this->object, 'foo'));

        $this->setToken($token2);

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

        $this->setToken($token2);

        $this->assertFalse($this->manager->isGranted('VIEW', $this->object, 'foo'));
    }
}
