<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager;

use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class ObjectAceManager extends AbstractAceManager
{
    /**
     * {@inheritdoc}
     */
    protected function getAces(AclInterface $acl, $field = null)
    {
        return $field ? $acl->getObjectFieldAces($field) : $acl->getObjectAces();
    }

    /**
     * {@inheritdoc}
     */
    protected function insertAce(
        MutableAclInterface $acl,
        SecurityIdentityInterface $sid,
        $mask,
        $field = null,
        $index = 0,
        $granting = true,
        $strategy = null
    ) {
        if ($field) {
            $acl->insertObjectFieldAce($field, $sid, $mask, $index, $granting, $strategy ?: $this->defaultStrategy);
        } else {
            $acl->insertObjectAce($sid, $mask, $index, $granting, $strategy ?: $this->defaultStrategy);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function updateAce(MutableAclInterface $acl, $index, $mask, $field = null, $strategy = null)
    {
        if ($field) {
            $acl->updateObjectFieldAce($index, $field, $mask, $strategy);
        } else {
            $acl->updateObjectAce($index, $mask, $strategy);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function deleteAce(MutableAclInterface $acl, $index, $field = null)
    {
        if ($field) {
            $acl->deleteObjectFieldAce($index, $field);
        } else {
            $acl->deleteObjectAce($index);
        }
    }
}
