<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Security\Acl\Domain;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\ObjectIdentityRetrievalStrategy as BaseObjectIdentityRetrievalStrategy;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class ObjectIdentityRetrievalStrategy extends BaseObjectIdentityRetrievalStrategy
{
    /**
     * {@inheritdoc}
     */
    public function getObjectIdentity($domainObject)
    {
        if ($domainObject instanceof ObjectIdentityInterface) {
            return $domainObject;
        } elseif (is_string($domainObject)) {
            if (!class_exists($domainObject)) {
                throw new \InvalidArgumentException(sprintf('Could not load class "%s"', $domainObject));
            }

            return new ObjectIdentity('class', $domainObject);
        }

        return parent::getObjectIdentity($domainObject);
    }
}
