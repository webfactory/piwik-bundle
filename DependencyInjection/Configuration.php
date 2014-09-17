<?php

namespace Webfactory\Bundle\PiwikBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('webfactory_piwik');

        $rootNode
            ->children()
            ->scalarNode('disabled')->defaultValue('%kernel.debug%')->end()
            ->scalarNode('piwik_host')->isRequired()->end()
            ->scalarNode('tracker_path')->defaultValue('/js/')->end()
            ->scalarNode('site_id')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
    
}
