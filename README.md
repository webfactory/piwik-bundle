piwik-bundle
============

[![Build Status](https://travis-ci.org/webfactory/piwik-bundle.svg?branch=master)](https://travis-ci.org/webfactory/piwik-bundle)
[![Coverage Status](https://coveralls.io/repos/webfactory/piwik-bundle/badge.png?branch=master)](https://coveralls.io/r/webfactory/piwik-bundle?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webfactory/piwik-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webfactory/piwik-bundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b6cd0ffd-e116-43c0-ba58-fbb70371bd6b/mini.png)](https://insight.sensiolabs.com/projects/b6cd0ffd-e116-43c0-ba58-fbb70371bd6b)

Symfony2 Bundle providing a Twig function for conditional Piwik tracking code depending on the runtime environment.


Installation
------------
Simply add the following to your composer.json (see http://getcomposer.org/):

    "require" :  {
        // ...
        "webfactory/piwik-bundle": "@stable"
    }

And enable the bundle in `app/AppKernel.php`:

    <?php
    // app/AppKernel.php
    
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Webfactory\Bundle\PiwikBundle\WebfactoryPiwikBundle(),
        );
    }


Usage
-----
At the end of your `base.html.twig` insert

	{{ piwik_code() }}


Configuration
-------------
You can configure the bundle in your `config.yml`. Full Example:

	webfactory_piwik:
	    site_id: 1                           # Required, no default. Must be set to the site id found in the piwik control panel
	    disabled: %kernel.debug%             # Required, has default. Usually, you only want to include the tracking code in a production environment
	    piwik_host: piwik.webfactory.de      # Required. no default. Hostname and path to the piwik host.
	    use_cacheable_tracking_script: true  # Required, has default. Whether to use piwik.js or js/. The latter one is served with headers that allow HTTP-Caching.



Credits, Copyright and License
------------------------------
Copyright 2012-2014 webfactory GmbH, Bonn. Code released under [the MIT license](LICENSE).

- <http://www.webfactory.de>
- <http://twitter.com/webfactory>
