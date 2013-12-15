<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) 1up GmbH
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Tests\EventListener;

use ProjectA\Bundle\AclBundle\Tests\Model\AbstractSecurityTest;
use ProjectA\Bundle\AclBundle\Tests\Model\SomeObject;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class DoctrineSubscriberTest extends AbstractSecurityTest
{
    protected $listener;

    public function setUp()
    {
        parent::setUp();

        $this->listener = $this->container->get('projecta_acl.doctrine_subscriber');
    }

    public function testPreRemoveListener()
    {
        $object = new SomeObject(1);

        $this->assertFalse($this->manager->isGranted('VIEW', $object));
        $this->assertFalse($this->manager->isGranted('EDIT', $object, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $object, 'bar'));

        $this->objectmanager->grant($object, MaskBuilder::MASK_VIEW, $this->token);
        $this->objectmanager->grant($object, MaskBuilder::MASK_EDIT, $this->token, 'foo');
        $this->objectmanager->grant($object, MaskBuilder::MASK_OWNER, $this->token, 'bar');

        $this->assertTrue($this->manager->isGranted('VIEW', $object));
        $this->assertTrue($this->manager->isGranted('EDIT', $object, 'foo'));
        $this->assertTrue($this->manager->isGranted('OWNER', $object, 'bar'));

        $args = $this->getMockBuilder('Doctrine\Common\Persistence\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $args->expects($this->any())
            ->method('getObject')
            ->will($this->returnValue($object))
        ;

        $this->listener->preRemove($args);

        $this->assertFalse($this->manager->isGranted('VIEW', $object));
        $this->assertFalse($this->manager->isGranted('EDIT', $object, 'foo'));
        $this->assertFalse($this->manager->isGranted('OWNER', $object, 'bar'));
    }
}
