![webfactory Logo](http://www.webfactory.de/bundles/webfactorytwiglayout/img/logo.png) WebfactoryPiwikBundle
============

[![Run Tests](https://github.com/webfactory/piwik-bundle/workflows/Run%20Tests/badge.svg)](https://github.com/webfactory/piwik-bundle/workflows/)
[![Coverage Status](https://coveralls.io/repos/webfactory/piwik-bundle/badge.png?branch=master)](https://coveralls.io/r/webfactory/piwik-bundle?branch=master)

A Symfony Bundle that helps you to use the Matomo (formerly known as Piwik) Open Analytics Platform with your project.

It contains a Twig function that can insert the tracking code into your website. Plus, you can turn it off with a simple configuration switch so you don't track your dev environment.


Installation
------------

    composer require webfactory/piwik-bundle

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

This will add the appropriate Matomo tracking code as [described in the API reference](https://developer.matomo.org/api-reference/tracking-javascript#where-can-i-find-the-piwik-tracking-code).

Configuration
-------------
You can configure the bundle in your `config.yml`. Full Example:

```yaml
webfactory_piwik:
    # Required, no default. Must be set to the site id found in the Matomo control panel
    site_id: 1
    # Required. no default. Hostname and path to the Matomo host.
    piwik_host: my.piwik.hostname
    # Optional, has default. Usually, you only want to include the tracking code in a production environment
    disabled: '%kernel.debug%'
    # Optional, has default. Path to the tracking script on the host.
    tracker_path: "/js/"
    # Optional, has default. Disable cookies in favor of GDPR
    # https://matomo.org/faq/new-to-piwik/how-do-i-use-matomo-analytics-without-consent-or-cookie-banner/ & https://matomo.org/faq/general/faq_157/
    disable_cookies: true
```

Add calls to the JavaScript tracker API
---------------------------------------

The [JavaScript tracking API](http://developer.matomo.org/api-reference/tracking-javascript) provides a lot of methods
for setting the page name, tracking search results, using custom variables and much more.

The generic `piwik()` function allows you to control the `_paq` variable and add additional API calls to it. For example,
in your Twig template, you can write

```twig
    <!-- Somewhere in your HTML, not necessarily at the end -->
    {{ piwik("setDocumentTitle", document.title) }}
    {{ piwik("trackGoal", 1) }}

    <!-- Then, at the end: -->
    {{ piwik_code() }}
    </body>
```

Note that when you call `trackSiteSearch`, this will automatically disable the `trackPageView` call made by default.
This is the [recommended](http://developer.matomo.org/api-reference/tracking-javascript#tracking-internal-search-keywords-categories-and-no-result-search-keywords)
behaviour.

Credits, Copyright and License
------------------------------

This code was written by webfactory GmbH, Bonn, Germany. We're a software development
agency with a focus on PHP (mostly [Symfony](http://github.com/symfony/symfony)). If you're a
developer looking for new challenges, we'd like to hear from you!

- <https://www.webfactory.de>
- <https://twitter.com/webfactory>

Copyright 2012 – 2020 webfactory GmbH, Bonn. Code released under [the MIT license](LICENSE).
