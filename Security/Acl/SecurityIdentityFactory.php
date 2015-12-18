<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Security\Acl;

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class SecurityIdentityFactory
{
    /**
     * @param string|TokenInterface|RoleInterface|UserInterface|SecurityIdentityInterface $identity
     *
     * @return RoleSecurityIdentity|UserSecurityIdentity
     *
     * @throws \InvalidArgumentException
     */
    public function createSecurityIdentity($identity)
    {
        if ($identity instanceof UserInterface) {
            return UserSecurityIdentity::fromAccount($identity);
        } elseif ($identity instanceof TokenInterface) {
            return UserSecurityIdentity::fromToken($identity);
        } elseif ($identity instanceof RoleInterface || is_string($identity)) {
            return new RoleSecurityIdentity($identity);
        } elseif ($identity instanceof SecurityIdentityInterface) {
            return $identity;
        }

        throw new \InvalidArgumentException(
            'Could not create a valid SecurityIdentity with the provided identity information'
        );
    }
}
