Upgrading notes
===============

Version 3.0
-----------

* Set config key disable_cookies default to true [BC break] (#31)

Version 2.2
-----------

* Make the Twig Extension available as a public service `webfactory_piwik.twig_extension` (#5)

Version 2.1
-----------

* Added the new Twig function piwik() to perform additional (arbitrary) API calls on the JavaScript tracker. 

Version 2.0
-----------

* [BC break] The configuration setting `use_cacheable_tracking_script` has been replaced by `tracker_path`. If `use_cacheable_tracking_script` was set to `true` (the default), use `js/`. Otherwise, use `piwik.js`. See issue #1.
