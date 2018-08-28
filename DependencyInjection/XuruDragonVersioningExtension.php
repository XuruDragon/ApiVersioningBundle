<?php

namespace XuruDragon\VersioningBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class XuruDragonVersioningExtension
 * @package XuruDragon\VersioningBundle\DependencyInjection
 */
class XuruDragonVersioningExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $listener_definition = $container->getDefinition('xuru_dragon_versioning.event_listener.versioning_listener');
        $listener_definition->replaceArgument(2, $config['header_name']);

        $factory_definition = $container->getDefinition('xuru_dragon_versioning.factory.changes_factory');
        $factory_definition->replaceArgument(1, $config['versions']);
    }
}
