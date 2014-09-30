![webfactory Logo](http://www.webfactory.de/bundles/webfactorytwiglayout/img/logo.png) WebfactoryPiwikBundle
============

[![Build Status](https://travis-ci.org/webfactory/piwik-bundle.svg?branch=master)](https://travis-ci.org/webfactory/piwik-bundle)
[![Coverage Status](https://coveralls.io/repos/webfactory/piwik-bundle/badge.png?branch=master)](https://coveralls.io/r/webfactory/piwik-bundle?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webfactory/piwik-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webfactory/piwik-bundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b6cd0ffd-e116-43c0-ba58-fbb70371bd6b/mini.png)](https://insight.sensiolabs.com/projects/b6cd0ffd-e116-43c0-ba58-fbb70371bd6b)

A Symfony2 Bundle that helps you to use the Piwik Open Analytics Platform with your project.

It contains a Twig function that can insert the tracking code into your website. Plus, you can turn it off with a simple configuration switch so you don't track your dev environment.


Installation
------------
Simply add the following to your composer.json (see http://getcomposer.org/):

```json
"require": {
    // ...
    "webfactory/piwik-bundle": "~2.0"
}
```

And enable the bundle in `app/AppKernel.php`:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Webfactory\Bundle\PiwikBundle\WebfactoryPiwikBundle(),
    );
}
```


Usage
-----
Somewhere in your views, right before the closing `</body>` tag, insert 

	{{ piwik_code() }}


Configuration
-------------
You can configure the bundle in your `config.yml`. Full Example:

```yaml
webfactory_piwik:
    # Required, no default. Must be set to the site id found in the Piwik control panel
    site_id: 1
    # Required, has default. Usually, you only want to include the tracking code in a production environment
    disabled: %kernel.debug%
    # Required. no default. Hostname and path to the piwik host.
    piwik_host: my.piwik.hostname
    # Required, has default. Path to the tracking script on the host.
    tracker_path: "/js/"
```


Credits, Copyright and License
------------------------------
Copyright 2012-2014 webfactory GmbH, Bonn. Code released under [the MIT license](LICENSE).

- <http://www.webfactory.de>
- <http://twitter.com/webfactory>
