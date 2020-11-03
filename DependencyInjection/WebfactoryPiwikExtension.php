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

        foreach (['disabled', 'piwik_host', 'tracker_path', 'site_id', 'disable_cookies'] as $configParameterKey) {
            if ($configParameterKey === 'disable_cookies' && $config[$configParameterKey] === null) {
                $config[$configParameterKey] = false;
                @trigger_error('The "disableCookies" configuration key is missing. Please define the "disableCookies" key explicit. In newer versions this will be "true" by default.', E_USER_DEPRECATED);
            }

            $container->setParameter("webfactory_piwik.$configParameterKey", $config[$configParameterKey]);
        }
    }
}
