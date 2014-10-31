Upgrading notes
===============

Version 2.1
-----------

* Added the new Twig function piwik() to perform additional (arbitrary) API calls on the JavaScript tracker. 

Version 2.0
-----------

* [BC break] The configuration setting `use_cacheable_tracking_script` has been replaced by `tracker_path`. If `use_cacheable_tracking_script` was set to `true` (the default), use `js/`. Otherwise, use `piwik.js`. See issue #1.
