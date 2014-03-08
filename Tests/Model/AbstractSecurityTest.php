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

namespace ProjectA\Bundle\AclBundle\Tests\Model;

use Doctrine\DBAL\Connection;
use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\ClassAceManager;
use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AceManager\ObjectAceManager;
use ProjectA\Bundle\AclBundle\Security\Acl\Manager\AclManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Acl\Dbal\Schema;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractSecurityTest extends WebTestCase
{
    protected $client;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ObjectAceManager
     */
    protected $objectmanager;

    /**
     * @var ClassAceManager
     */
    protected $classmanager;

    /**
     * @var AclManager
     */
    protected $manager;

    /**
     * @var UsernamePasswordToken
     */
    protected $token;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var SomeObject
     */
    protected $object;

    protected function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();

        $this->token = $this->createToken();
        $this->container->get('security.context')->setToken($this->token);

        $this->connection = $this->container->get('database_connection');

        $options = array(
            'oid_table_name' => 'acl_object_identities',
            'oid_ancestors_table_name' => 'acl_object_identity_ancestors',
            'class_table_name' => 'acl_classes',
            'sid_table_name' => 'acl_security_identities',
            'entry_table_name' => 'acl_entries',
        );

        $schema = new Schema($options);

        foreach ($schema->toSql($this->connection->getDatabasePlatform()) as $sql) {
            $this->connection->exec($sql);
        }

        $this->manager = $this->container->get('projecta_acl.manager');
        $this->objectmanager = $this->manager->manageObjectAces();
        $this->classmanager = $this->manager->manageClassAces();

        $this->object = new SomeObject(1);
    }

    /**
     * @param string[]|RoleInterface[] $roles
     *
     * @return UsernamePasswordToken
     */
    protected function createToken(array $roles = array())
    {
        $roles[] = 'ROLE_USER';

        return new UsernamePasswordToken(uniqid(), null, 'main', $roles);
    }
}
