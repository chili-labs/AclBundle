<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * EventSubscriber for doctrine that removes acl entries on
 * removal of the referenced domain object.
 *
 * This subscriber is not bound to any specific object mapper,
 * but you will need to install either one to get this working.
 * For example ORM or ODM
 *
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class DoctrineSubscriber extends ContainerAware implements EventSubscriber
{
    /**
     * @var bool
     */
    private $isActive;

    /**
     * @param bool $isActive
     */
    public function __construct($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        if ($this->isActive) {
            $this->container->get('projecta_acl.ace.objectmanager')->deleteAcl($args->getObject());
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
