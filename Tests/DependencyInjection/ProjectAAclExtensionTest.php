<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\Tests\DependencyInjection;

use ProjectA\Bundle\AclBundle\Tests\Model\AbstractSecurityTest;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class ProjectAAclExtensionTest extends AbstractSecurityTest
{
    public function testIfTestSuiteLoads()
    {
        $this->assertTrue(true);
    }

    public function testIfOrphanRemovalParameterIsSet()
    {
        $this->assertTrue(is_bool($this->container->getParameter('projecta_acl.remove_orphans')));
    }

    public function testIfPermissionStrategyParameterIsSet()
    {
        $this->assertTrue(
            'any' == $this->container->getParameter('projecta_acl.default_strategy') ||
            'all' == $this->container->getParameter('projecta_acl.default_strategy') ||
            'equal' == $this->container->getParameter('projecta_acl.default_strategy')
        );
    }

    public function testIfContainerExists()
    {
        $this->assertNotNull($this->client);
        $this->assertNotNull($this->container);
    }

    public function testIfSecurityContextLoads()
    {
        $aclProvider = $this->container->get('security.context');
        $this->assertTrue($aclProvider->isGranted('ROLE_USER'));
        $this->assertFalse($aclProvider->isGranted('ROLE_ADMIN'));
    }
}
