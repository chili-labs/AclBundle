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

use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\ClassAceManager;
use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\ObjectAceManager;
use Symfony\Component\Security\Acl\Voter\FieldVote;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class AclManager
{
    /**
     * @var SecurityContextInterface
     */
    private $context;

    /**
     * @var ClassAceManager
     */
    private $classAceManager;

    /**
     * @var ObjectAceManager
     */
    private $objectAceManager;

    /**
     * @param SecurityContextInterface $context
     * @param ClassAceManager          $classAceManager
     * @param ObjectAceManager         $objectAceManager
     */
    public function __construct(SecurityContextInterface $context, ClassAceManager $classAceManager, ObjectAceManager $objectAceManager)
    {
        $this->context = $context;
        $this->classAceManager = $classAceManager;
        $this->objectAceManager = $objectAceManager;
    }

    /**
     * @return ClassAceManager
     */
    public function manageClassAces()
    {
        return $this->classAceManager;
    }

    /**
     * @return ObjectAceManager
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
     */
    public function isGranted($attributes, $object = null, $field = null)
    {
        if ($field) {
            $object = new FieldVote($object, $field);
        }

        return $this->context->isGranted($attributes, $object);
    }
}
