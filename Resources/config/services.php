<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function(ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set(\Webfactory\Bundle\PiwikBundle\Twig\Extension::class)
        ->public()
        ->args([
            '%webfactory_piwik.disabled%',
            '%webfactory_piwik.site_id%',
            '%webfactory_piwik.piwik_host%',
            '%webfactory_piwik.tracker_path%',
            '%webfactory_piwik.disable_cookies%',
            '%webfactory_piwik.enable_do_not_track%',
        ])
        ->tag('twig.extension');
};
