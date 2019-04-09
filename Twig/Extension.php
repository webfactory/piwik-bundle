<?php

namespace Webfactory\Bundle\PiwikBundle\Twig;

class Extension extends \Twig_Extension
{
    protected $disabled;
    protected $siteId;
    protected $piwikHost;
    protected $trackerPath;

    protected $paqs = array();

    function __construct($disabled, $siteId, $piwikHost, $trackerPath)
    {
        $this->disabled = $disabled;
        $this->siteId = $siteId;
        $this->piwikHost = rtrim($piwikHost, '/');
        $this->trackerPath = ltrim($trackerPath, '/');
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('piwik_code', array($this, 'piwikCode'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('piwik', array($this, 'piwikPush'))
        );
    }

    public function piwikPush()
    {
        $this->paqs[] = func_get_args();
    }

    public function piwikCode()
    {
        if ($this->disabled) {
            return '<!-- Piwik is disabled due to webfactory_piwik.disabled=true in your configuration -->';
        }

        $this->addDefaultApiCalls();

        $paq = json_encode($this->paqs);

        $piwikCode = <<<EOT
<!-- Piwik -->
<script type="text/javascript">//<![CDATA[
var _paq = (_paq||[]).concat({$paq});
_paq.push(["setDoNotTrack", true]);
_paq.push(['trackPageView']);
(function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://{$this->piwikHost}/";
    _paq.push(["setTrackerUrl", u+"{$this->trackerPath}"]);
    _paq.push(["setSiteId", "{$this->siteId}"]);
EOT;
        if($this->trackerPath !== 'piwik.php') {
            $piwikCode .= <<<EOT
    _paq.push(['setAPIUrl', u]);
EOT;
        }
        $piwikCode .= <<<EOT
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"{$this->trackerPath}"; s.parentNode.insertBefore(g,s);
})();
//]]></script>
<noscript><p><img src="//{$this->piwikHost}/piwik.php?idsite={$this->siteId}&rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
EOT;

        return $piwikCode;
    }

    protected function addDefaultApiCalls()
    {
        $this->paqs[] = array('enableLinkTracking');

        foreach ($this->paqs as $paq) {
            if ($paq[0] == 'trackSiteSearch') {
                /*
                 * It is recommended *not* to "trackPageView" for "trackSiteSearch" pages.
                 * See http://developer.piwik.org/api-reference/tracking-javascript#tracking-internal-search-keywords-categories-and-no-result-search-keywords
                 * or http://piwik.org/docs/site-search/#track-site-search-using-the-javascript-tracksitesearch-function.
                 */
                return; // Do not add 'trackPageView'
            }
        }

        $this->paqs[] = array('trackPageView');
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'webfactory_piwik';
    }
}
