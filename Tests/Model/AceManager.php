<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Tests\Model;

use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\AbstractAceManager;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\EntryInterface;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

class AceManager extends AbstractAceManager
{
    /**
     * @param AclInterface $acl
     * @param bool $field
     *
     * @return EntryInterface[]
     */
    protected function getAces(AclInterface $acl, $field = null)
    {
        return [];
    }

    /**
     * @param MutableAclInterface $acl
     * @param SecurityIdentityInterface $sid
     * @param int $mask
     * @param string $field
     * @param int $index
     * @param bool $granting
     * @param string $strategy
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
    }

    /**
     * @param MutableAclInterface $acl
     * @param int $index
     * @param int $mask
     * @param string $field
     * @param string $strategy
     */
    protected function updateAce(MutableAclInterface $acl, $index, $mask, $field = null, $strategy = null)
    {
    }

    /**
     * @param MutableAclInterface $acl
     * @param int $index
     * @param string $field
     */
    protected function deleteAce(MutableAclInterface $acl, $index, $field = null)
    {
    }
}
