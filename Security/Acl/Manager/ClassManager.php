<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Security\Acl\Manager;

use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

/**
 * @author Daniel Tschinder <daniel.tschinder@project-a.com>
 */
class ClassManager extends AbstractManager
{
    /**
     * {@inheritdoc}
     */
    protected function getAces(AclInterface $acl, $field = null)
    {
        return $field ? $acl->getClassFieldAces($field) : $acl->getClassAces();
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
            $acl->insertClassFieldAce($field, $sid, $mask, $index, $granting, $strategy ?: $this->defaultStrategy);
        } else {
            $acl->insertClassAce($sid, $mask, $index, $granting, $strategy ?: $this->defaultStrategy);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function updateAce(MutableAclInterface $acl, $index, $mask, $field = null, $strategy = null)
    {
        if ($field) {
            $acl->updateClassAce($index, $mask, $strategy);
        } else {
            $acl->updateClassFieldAce($index, $field, $mask, $strategy);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function deleteAce(MutableAclInterface $acl, $index, $field = null)
    {
        if ($field) {
            $acl->deleteClassAce($index);
        } else {
            $acl->deleteClassFieldAce($index, $field);
        }
    }
}
