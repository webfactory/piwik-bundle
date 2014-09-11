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
            ->scalarNode('piwik_host')->defaultValue('piwik.webfactory.de')->info('Full URL to the piwik host (i.e. piwik.webfactory.de)')->end()
            ->booleanNode('use_cacheable_tracking_script')->defaultTrue()->info('Whether to use piwik.js or js/. The later one is served with headers that allow HTTP-Caching.')->end()
            ->scalarNode('site_id')->isRequired()->info('The site ID found in the piwik control panel')->end()
            ->end();

        return $treeBuilder;
    }
    
}
