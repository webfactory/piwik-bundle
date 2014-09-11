piwik-bundle
============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webfactory/piwik-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webfactory/piwik-bundle/?branch=master)

Symfony2 Bundle that adds a twig-function for the Piwik tracking code


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


