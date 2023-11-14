<?php

namespace Webfactory\Bundle\PiwikBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('webfactory_piwik');
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('webfactory_piwik');
        }

        $rootNode
            ->children()
            ->scalarNode('disabled')->defaultValue('%kernel.debug%')->end()
            ->scalarNode('piwik_host')->isRequired()->end()
            ->scalarNode('tracker_path')->defaultValue('/js/')->end()
            ->scalarNode('site_id')->isRequired()->end()
            ->scalarNode('disable_cookies')->defaultValue(true)->end()
            ->scalarNode('enable_do_not_track')->defaultValue(true)->info('Include ["setDoNotTrack", true] in default _paqs')->end()
            ->end();

        return $treeBuilder;
    }
}
