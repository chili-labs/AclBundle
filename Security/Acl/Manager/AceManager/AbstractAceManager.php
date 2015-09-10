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

use ProjectA\Bundle\AclBundle\Security\Acl\AclRepository;
use ProjectA\Bundle\AclBundle\Security\Acl\SecurityIdentityFactory;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\EntryInterface;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
abstract class AbstractAceManager implements AceManagerInterface
{
    /**
     * @var string
     */
    protected $defaultStrategy;

    /**
     * @var AclRepository
     */
    private $aclRepository;

    /**
     * @var ObjectIdentityRetrievalStrategyInterface
     */
    private $objectIdentityRetrievalStrategy;

    /**
     * @var MutableAclProviderInterface
     */
    private $provider;

    /**
     * @var SecurityIdentityFactory
     */
    private $securityIdentityFactory;

    /**
     * @param MutableAclProviderInterface              $provider
     * @param ObjectIdentityRetrievalStrategyInterface $objectIdentityRetrievalStrategy
     * @param AclRepository                            $aclRepository
     * @param SecurityIdentityFactory                  $securityIdentityFactory
     * @param string                                   $defaultStrategy
     */
    public function __construct(
        MutableAclProviderInterface $provider,
        ObjectIdentityRetrievalStrategyInterface $objectIdentityRetrievalStrategy,
        AclRepository $aclRepository,
        SecurityIdentityFactory $securityIdentityFactory,
        $defaultStrategy
    ) {
        $this->provider = $provider;
        $this->objectIdentityRetrievalStrategy = $objectIdentityRetrievalStrategy;
        $this->defaultStrategy = $defaultStrategy;
        $this->aclRepository = $aclRepository;
        $this->securityIdentityFactory = $securityIdentityFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function grant($object, $mask, $identity, $field = null, $strategy = null)
    {
        $this->validateObject($object);

        $sid = $this->securityIdentityFactory->createSecurityIdentity($identity);
        $acl = $this->aclRepository->findOrCreateAcl($object);

        $this->insertAce($acl, $sid, $mask, $field, 0, true, $strategy ?: $this->defaultStrategy);
        $this->provider->updateAcl($acl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revoke($object, $mask, $identity, $field = null)
    {
        $this->validateObject($object);

        if (null === ($acl = $this->aclRepository->findAcl($object))) {
            return $this;
        }

        $sid = $this->securityIdentityFactory->createSecurityIdentity($identity);
        $aces = $this->getAces($acl, $field);

        /* @var EntryInterface $ace */
        foreach ($aces as $index => $ace) {
            if ($sid->equals($ace->getSecurityIdentity())) {
                $this->updateAce($acl, $index, $ace->getMask() & ~$mask, $field);
            }
        }
        $this->provider->updateAcl($acl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAllForIdentity($object, $identity, $field = null)
    {
        $this->validateObject($object);

        if (null === ($acl = $this->aclRepository->findAcl($object))) {
            return $this;
        }

        $sid = $this->securityIdentityFactory->createSecurityIdentity($identity);
        $aces = $this->getAces($acl, $field);
        $aces = array_reverse($aces, true);

        /* @var EntryInterface $ace */
        foreach ($aces as $index => $ace) {
            if ($sid->equals($ace->getSecurityIdentity())) {
                $this->deleteAce($acl, $index, $field);
            }
        }
        $this->provider->updateAcl($acl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAll($object, $field = null)
    {
        $this->validateObject($object);

        if (null === ($acl = $this->aclRepository->findAcl($object))) {
            return $this;
        }

        $aces = $this->getAces($acl, $field);
        $aces = array_reverse($aces, true);

        /* @var EntryInterface $ace */
        foreach (array_keys($aces) as $index) {
            $this->deleteAce($acl, $index, $field);
        }
        $this->provider->updateAcl($acl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAcl($object)
    {
        $oid = $this->objectIdentityRetrievalStrategy->getObjectIdentity($object);
        $this->provider->deleteAcl($oid);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function preload($objects)
    {
        $oids = array();
        foreach ($objects as $object) {
            $oids[] = $this->objectIdentityRetrievalStrategy->getObjectIdentity($object);
        }
        $this->provider->findAcls($oids);

        return $this;
    }

    /**
     * @param AclInterface $acl
     * @param string       $field
     *
     * @return EntryInterface[]
     */
    abstract protected function getAces(AclInterface $acl, $field = null);

    /**
     * @param MutableAclInterface       $acl
     * @param SecurityIdentityInterface $sid
     * @param int                       $mask
     * @param string                    $field
     * @param int                       $index
     * @param bool                      $granting
     * @param string                    $strategy
     */
    abstract protected function insertAce(
        MutableAclInterface $acl,
        SecurityIdentityInterface $sid,
        $mask,
        $field = null,
        $index = 0,
        $granting = true,
        $strategy = null
    );

    /**
     * @param MutableAclInterface $acl
     * @param int                 $index
     * @param int                 $mask
     * @param string              $field
     * @param string              $strategy
     */
    abstract protected function updateAce(MutableAclInterface $acl, $index, $mask, $field = null, $strategy = null);

    /**
     * @param MutableAclInterface $acl
     * @param int                 $index
     * @param string              $field
     */
    abstract protected function deleteAce(MutableAclInterface $acl, $index, $field = null);

    /**
     * @param object $object
     */
    abstract protected function validateObject($object);
}
