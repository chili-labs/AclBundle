<?php

namespace Oneup\AclBundle\Tests\DependencyInjection;

use ProjectA\Bundle\AclBundle\Tests\Model\AbstractSecurityTest;

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
}
