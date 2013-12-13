<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) 1up GmbH
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Security\Acl\Manager;

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\EntryInterface;
use Symfony\Component\Security\Acl\Model\MutableAclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Daniel Tschinder <daniel.tschinder@project-a.com>
 */
abstract class AbstractManager implements ManagerInterface
{
    /**
     * @var MutableAclProviderInterface
     */
    private $provider;

    /**
     * @var SecurityContextInterface
     */
    private $context;

    /**
     * @var ObjectIdentityRetrievalStrategyInterface
     */
    private $objectIdentityStrategy;

    /**
     * @var string
     */
    protected $defaultStrategy;

    /**
     * @param MutableAclProviderInterface $provider
     * @param SecurityContextInterface $context
     * @param ObjectIdentityRetrievalStrategyInterface $objectIdentityStrategy
     * @param string $defaultStrategy
     */
    public function __construct(MutableAclProviderInterface $provider, SecurityContextInterface $context, ObjectIdentityRetrievalStrategyInterface $objectIdentityStrategy, $defaultStrategy)
    {
        $this->provider = $provider;
        $this->context = $context;
        $this->objectIdentityStrategy = $objectIdentityStrategy;
        $this->defaultStrategy = $defaultStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function grant($object, $mask, $identity, $field = null, $strategy = null)
    {
        $sid = $this->createSecurityIdentity($identity);
        $acl = $this->findOrCreateAcl($object);

        $this->insertAce($acl, $sid, $mask, $field, 0, true, $strategy ?: $this->defaultStrategy);
        $this->provider->updateAcl($acl);
    }

    /**
     * {@inheritdoc}
     */
    public function overwrite($object, $mask, $identity, $field = null, $strategy = null)
    {
        $this->revokeAllForIdentity($object, $identity, $field);
        $this->grant($object, $mask, $identity, $field, $strategy);
    }

    /**
     * {@inheritdoc}
     */
    public function revoke($object, $mask, $identity, $field = null)
    {
        $sid = $this->createSecurityIdentity($identity);

        $acl = $this->findAcl($object);
        $aces = $this->getAces($acl, !empty($field));

        /* @var EntryInterface $ace */
        foreach ($aces as $index => $ace) {
            if ($sid->equals($ace->getSecurityIdentity())) {
                $this->updateAce($acl, $index, $ace->getMask() & ~$mask, $field);
            }
        }

        $this->provider->updateAcl($acl);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAllForIdentity($object, $identity, $field = null)
    {
        if (null === ($acl = $this->findAcl($object))) {
            return;
        }

        $sid = $this->createSecurityIdentity($identity);
        $aces = $this->getAces($acl, $field);

        /* @var EntryInterface $ace */
        foreach ($aces as $index => $ace) {
            if ($sid->equals($ace->getSecurityIdentity())) {
                $this->deleteAce($acl, $index, $field);
            }
        }

        $this->provider->updateAcl($acl);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAll($object, $field = null)
    {
        if (null === ($acl = $this->findAcl($object))) {
            return;
        }

        $aces = $this->getAces($acl, $field);

        /* @var EntryInterface $ace */
        foreach (array_keys($aces) as $index) {
            $this->deleteAce($acl, $index, $field);
        }

        $this->provider->updateAcl($acl);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAcl($object)
    {
        $oid = $this->createObjectIdentity($object);
        $this->provider->deleteAcl($oid);
    }

    /**
     * @param AclInterface $acl
     * @param bool $field
     *
     * @return EntryInterface[]
     */
    abstract protected function getAces(AclInterface $acl, $field = null);

    /**
     * @param MutableAclInterface $acl
     * @param SecurityIdentityInterface $sid
     * @param int $mask
     * @param string $field
     * @param int $index
     * @param bool $granting
     * @param string $strategy
     */
    abstract protected function insertAce(MutableAclInterface $acl, SecurityIdentityInterface $sid, $mask, $field = null, $index = 0, $granting = true, $strategy = null);

    /**
     * @param MutableAclInterface $acl
     * @param int $index
     * @param int $mask
     * @param string $field
     * @param string $strategy
     */
    abstract protected function updateAce(MutableAclInterface $acl, $index, $mask, $field = null, $strategy = null);

    /**
     * @param MutableAclInterface $acl
     * @param int $index
     * @param string $field
     */
    abstract protected function deleteAce(MutableAclInterface $acl, $index, $field = null);

    /**
     * @param object $object
     * @return MutableAclInterface
     */
    protected function findAcl($object)
    {
        $identity = $this->createObjectIdentity($object);

        try {
            $acl = $this->provider->findAcl($identity);
        } catch (AclNotFoundException $e) {
            $acl = null;
        }

        return $acl;
    }

    /**
     * @param object $object
     * @return MutableAclInterface
     */
    protected function findOrCreateAcl($object)
    {
        $acl = $this->findAcl($object);
        if (null === $acl) {
            $identity = $this->createObjectIdentity($object);
            $acl = $this->provider->createAcl($identity);
        }

        return $acl;
    }

    /**
     * @param string|TokenInterface|RoleInterface|UserInterface $identity
     *
     * @return RoleSecurityIdentity|UserSecurityIdentity
     *
     * @throws \InvalidArgumentException
     */
    protected function createSecurityIdentity($identity)
    {
        if ($identity instanceof UserInterface) {
            $securityIdentity = UserSecurityIdentity::fromAccount($identity);
        } elseif ($identity instanceof TokenInterface) {
            $securityIdentity = UserSecurityIdentity::fromToken($identity);
        } elseif ($identity instanceof RoleInterface || is_string($identity)) {
            $securityIdentity = new RoleSecurityIdentity($identity);
        } else {
            throw new \InvalidArgumentException('Could not create a valid SecurityIdentity with the provided identity information');
        }

        return $securityIdentity;
    }

    /**
     * @param object $object
     *
     * @return ObjectIdentityInterface
     */
    protected function createObjectIdentity($object)
    {
        return $this->objectIdentityStrategy->getObjectIdentity($object);
    }
}
