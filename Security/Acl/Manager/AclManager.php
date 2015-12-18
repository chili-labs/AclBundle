<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Security\Acl\Manager;

use ProjectA\Bundle\AclBundle\Security\Acl\AclRepository;
use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\ClassAceManager;
use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\ObjectAceManager;
use Symfony\Component\Security\Acl\Voter\FieldVote;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class AclManager
{
    /**
     * @var SecurityContextInterface|AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var ClassAceManager
     */
    private $classAceManager;

    /**
     * @var ObjectAceManager
     */
    private $objectAceManager;

    /**
     * @var AclRepository
     */
    private $aclRepository;

    /**
     * @param SecurityContextInterface|AuthorizationChecker $authorizationChecker
     * @param ClassAceManager                               $classAceManager
     * @param ObjectAceManager                              $objectAceManager
     * @param AclRepository                                 $aclRepository
     */
    public function __construct(
        /* AuthorizationCheckerInterface */
        $authorizationChecker,
        ClassAceManager $classAceManager,
        ObjectAceManager $objectAceManager,
        AclRepository $aclRepository
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->classAceManager = $classAceManager;
        $this->objectAceManager = $objectAceManager;
        $this->aclRepository = $aclRepository;
    }

    /**
     * @return ClassAceManager
     *
     * @api
     */
    public function manageClassAces()
    {
        return $this->classAceManager;
    }

    /**
     * @return ObjectAceManager
     *
     * @api
     */
    public function manageObjectAces()
    {
        return $this->objectAceManager;
    }

    /**
     * Checks if the attributes are granted against the current token.
     *
     * @param mixed  $attributes
     * @param object $object
     * @param string $field
     *
     * @return bool
     *
     * @api
     */
    public function isGranted($attributes, $object = null, $field = null)
    {
        if ($object) {
            // ensure the acl is created, as otherwise class aces do not work
            $this->aclRepository->findOrCreateAcl($object);
        }

        if ($field) {
            $object = new FieldVote($object, $field);
        }

        return $this->authorizationChecker->isGranted($attributes, $object);
    }
}
