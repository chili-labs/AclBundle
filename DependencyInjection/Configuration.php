<?php

namespace ProjectA\Bundle\AclBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Daniel Tschinder <daniel.tschinder@project-a.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('project_a_acl');

        $rootNode
            ->children()
                ->booleanNode('remove_orphans')->defaultTrue()->end()
                ->enumNode('default_strategy')
                    ->values(array('any', 'all', 'equal'))
                    ->defaultValue('all')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
