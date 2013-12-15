<?php

namespace ProjectA\Bundle\AclBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $manager = $this->container->get('projecta_acl.ace.objectmanager');
        $remove = $this->container->getParameter('projecta_acl.remove_orphans');

        if ($remove) {
            $manager->deleteAcl($args->getObject());
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
