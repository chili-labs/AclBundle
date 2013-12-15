<?php

namespace ProjectA\Bundle\AclBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Daniel Tschinder <daniel.tschinder@project-a.com>
 */
class ProjectAAclExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('acl.xml');
        $loader->load('doctrine.xml');

        $strategy = constant(
            sprintf(
                'Symfony\Component\Security\Acl\Domain\PermissionGrantingStrategy::%s',
                strtoupper($config['default_strategy'])
            )
        );

        $container->setParameter('projecta_acl.remove_orphans', $config['remove_orphans']);
        $container->setParameter('projecta_acl.default_strategy', $strategy);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'projecta_acl';
    }
}
