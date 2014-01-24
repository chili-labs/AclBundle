<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager;

use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Daniel Tschinder <daniel.tschinder@project-a.com>
 */
interface AceManagerInterface
{
    /**
     * Grant a permission for an object
     *
     * @param object                                                                      $object
     * @param int                                                                         $mask
     * @param string|TokenInterface|RoleInterface|UserInterface|SecurityIdentityInterface $identity
     * @param string                                                                      $field
     * @param string                                                                      $strategy
     *
     * @return self
     */
    public function grant($object, $mask, $identity, $field = null, $strategy = null);

    /**
     * Revoke all permissions and grant the supplied one only
     *
     * @param object                                                                      $object
     * @param int                                                                         $mask
     * @param string|TokenInterface|RoleInterface|UserInterface|SecurityIdentityInterface $identity
     * @param string                                                                      $field
     * @param string                                                                      $strategy
     *
     * @return self
     */
    public function overwrite($object, $mask, $identity, $field = null, $strategy = null);

    /**
     * Revoke a permission for an object
     *
     * @param object                                                                      $object
     * @param int                                                                         $mask
     * @param string|TokenInterface|RoleInterface|UserInterface|SecurityIdentityInterface $identity
     * @param string                                                                      $field
     *
     * @return self
     */
    public function revoke($object, $mask, $identity, $field = null);

    /**
     * Revoke all permissions for an identity
     *
     * @param object                                                                      $object
     * @param string|TokenInterface|RoleInterface|UserInterface|SecurityIdentityInterface $identity
     * @param string                                                                      $field
     *
     * @return self
     */
    public function revokeAllForIdentity($object, $identity, $field = null);

    /**
     * Revoke all permissions for an object
     *
     * @param object $object
     * @param string $field
     *
     * @return self
     */
    public function revokeAll($object, $field = null);

    /**
     * Delete the complete acl for an object
     *
     * All AccessControlEntries and the ObjectIdentity for an object
     * are deleted from storage
     *
     * @param object $object
     *
     * @return self
     */
    public function deleteAcl($object);

    /**
     * Preload Acls for all the objects
     *
     * @param object[] $objects
     *
     * @return self
     */
    public function preload($objects);
}
