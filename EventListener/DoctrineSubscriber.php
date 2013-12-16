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

namespace ProjectA\Bundle\AclBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\ObjectAceManager;

/**
 * EventSubscriber for doctrine that removes acl entries on
 * removal of the referenced domain object.
 *
 * This subscriber is not bound to any specific object mapper,
 * but you will need to install either one to get this working.
 * For example ORM or ODM
 *
 * @author Daniel Tschinder <daniel.tschinder@project-a.com>
 */
class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var ObjectAceManager
     */
    private $objectManager;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @param ObjectAceManager $objectManager
     * @param bool             $isActive
     */
    public function __construct(ObjectAceManager $objectManager, $isActive)
    {
        $this->objectManager = $objectManager;
        $this->isActive = $isActive;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if ($this->isActive) {
            $this->objectManager->deleteAcl($args->getObject());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array('preRemove');
    }
}
