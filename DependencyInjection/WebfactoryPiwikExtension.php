<?php

namespace Webfactory\Bundle\PiwikBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class WebfactoryPiwikExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new XmlFileLoader($container, $locator);
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach (array('disabled', 'piwik_host', 'use_cacheable_tracking_script', 'site_id') as $configParameterKey) {
            $container->setParameter("webfactory_piwik.$configParameterKey", $config[$configParameterKey]);
        }
    }

}