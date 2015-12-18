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

use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class AclRepository
{
    /**
     * @var ObjectIdentityRetrievalStrategyInterface
     */
    private $objectIdentityRetrievalStrategy;

    /**
     * @var MutableAclProviderInterface
     */
    private $provider;

    /**
     * @param MutableAclProviderInterface              $provider
     * @param ObjectIdentityRetrievalStrategyInterface $objectIdentityRetrievalStrategy
     */
    public function __construct(
        MutableAclProviderInterface $provider,
        ObjectIdentityRetrievalStrategyInterface $objectIdentityRetrievalStrategy
    ) {
        $this->objectIdentityRetrievalStrategy = $objectIdentityRetrievalStrategy;
        $this->provider = $provider;
    }

    /**
     * @param object $object
     *
     * @return MutableAclInterface
     */
    public function findAcl($object)
    {
        $identity = $this->objectIdentityRetrievalStrategy->getObjectIdentity($object);

        try {
            $acl = $this->provider->findAcl($identity);
            $this->checkAclType($acl);
        } catch (AclNotFoundException $e) {
            $acl = null;
        }

        return $acl;
    }

    /**
     * @param object $object
     *
     * @return MutableAclInterface
     */
    public function findOrCreateAcl($object)
    {
        $acl = $this->findAcl($object);
        if (null === $acl) {
            $identity = $this->objectIdentityRetrievalStrategy->getObjectIdentity($object);
            $acl = $this->provider->createAcl($identity);
            $this->checkAclType($acl);
        }

        return $acl;
    }

    /**
     * @param AclInterface $acl
     *
     * @throws \LogicException if the provider is not creating mutable acl classes
     */
    private function checkAclType(AclInterface $acl)
    {
        if (!$acl instanceof MutableAclInterface) {
            throw new \LogicException('The acl provider needs to create acls of type MutableAclInterface');
        }
    }
}
