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

        if (null === $config['disable_cookies']) {
            $config['disable_cookies'] = false;
            @trigger_error(
                'The "disableCookies" configuration key is missing. In the next major version, it will default to "true". 
                Please configure the "disableCookies" key explicitly if this is not what you want.',
                E_USER_DEPRECATED
            );
        }

        foreach (['disabled', 'piwik_host', 'tracker_path', 'site_id', 'disable_cookies'] as $configParameterKey) {
            $container->setParameter("webfactory_piwik.$configParameterKey", $config[$configParameterKey]);
        }
    }
}
