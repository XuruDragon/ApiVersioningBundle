<?php

namespace XuruDragon\ApiVersioningBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('xurudragon_versioning');

        $default_classname = 'XuruDragon\\VersioningBundle\\Changes\\Changes001';

        $rootNode
            ->fixXmlConfig('version')
            ->children()
            ->scalarNode('header_name')
            ->info('Header name that should be sent to the api to processing versioning changes')
            ->defaultValue('X-Accept-Version')
            ->cannotBeEmpty()
            ->end() //header_name definition
            ->arrayNode('versions')
            ->info("Array of version changes that breaks backward compatibility\nThe array should be `desc` ordered.\nThe newest version should be the first and the oldest the last one")
            ->prototype('array') //since Symfony 3.3 cwe can use arrayPrototype but here it's use for legacy compatibility
            ->children()
            ->scalarNode('version_number')
            ->isRequired()
            ->cannotBeEmpty()
            ->defaultValue('0.0.1')
            ->end()
            ->scalarNode('changes_class')
            ->isRequired()
            ->cannotBeEmpty()
            ->defaultValue($default_classname)
            ->end()
            ->end()
            ->end() //versions array
            ->end()
        ;

        return $treeBuilder;
    }
}
