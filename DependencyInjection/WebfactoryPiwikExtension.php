<?php

namespace Webfactory\Bundle\PiwikBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WebfactoryPiwikExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new XmlFileLoader($container, $locator);
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach (['disabled', 'piwik_host', 'tracker_path', 'site_id', 'disable_cookies', 'enable_do_not_track'] as $configParameterKey) {
            $container->setParameter("webfactory_piwik.$configParameterKey", $config[$configParameterKey]);
        }
    }
}
