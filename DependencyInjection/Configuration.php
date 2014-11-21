<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('projecta_acl');

        $rootNode
            ->children()
                ->booleanNode('remove_orphans')->defaultFalse()->end()
                ->enumNode('default_strategy')
                    ->values(array('all', 'any', 'equal'))
                    ->defaultValue('all')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
