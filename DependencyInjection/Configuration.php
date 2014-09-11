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
            ->scalarNode('piwik_host')->defaultValue('piwik.webfactory.de')->end()
            ->booleanNode('use_cacheable_tracking_script')->defaultTrue()->end()
            ->scalarNode('site_id')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
    
}
