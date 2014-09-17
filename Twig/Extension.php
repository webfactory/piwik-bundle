<?php

namespace Webfactory\Bundle\PiwikBundle\Twig;

class Extension extends \Twig_Extension
{
    protected $disabled;
    protected $siteId;
    protected $piwikHost;
    protected $trackerPath;

    function __construct($disabled, $siteId, $piwikHost, $trackerPath)
    {
        $this->disabled = $disabled;
        $this->siteId = $siteId;
        $this->piwikHost = $piwikHost;
        $this->trackerPath = $trackerPath;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('piwik_code', array($this, 'piwikCode'), array('is_safe' => array('html')))
        );
    }

    public function piwikCode()
    {
        if ($this->disabled) {
            return '<!-- Piwik is disabled due to webfactory_piwik.disabled=true in your configuration -->';
        }

        $piwikCode = <<<EOT
<!-- Piwik -->
<script type="text/javascript">//<![CDATA[
var _paq = _paq || [];
_paq.push(["trackPageView"]);
_paq.push(["enableLinkTracking"]);

(function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://{$this->piwikHost}/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "{$this->siteId}"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"{$this->trackerPath}"; s.parentNode.insertBefore(g,s);
})();
//]]></script>
<!-- End Piwik Code -->
EOT;

        return $piwikCode;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'webfactory_piwik';
    }
}
