<?php

namespace Webfactory\Bundle\PiwikBundle\Twig;

class Extension extends \Twig_Extension
{
    protected $disabled;
    protected $siteId;
    protected $piwikHost;
    protected $trackerPath;

    protected $paqs = array(
        array('trackPageView'),
        array('enableLinkTracking')
    );

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
        $args = func_get_args();

        $this->paqs[] = $args;
        $func = $args[0];

        /*
         * It is recommended *not* to "trackPageView" for "trackSiteSearch" pages.
         * See http://developer.piwik.org/api-reference/tracking-javascript#tracking-internal-search-keywords-categories-and-no-result-search-keywords
         * or http://piwik.org/docs/site-search/#track-site-search-using-the-javascript-tracksitesearch-function.
         */
        if ($func == 'trackSiteSearch') {
            foreach ($this->paqs as $offset => $p) {
                if ($p[0] == 'trackPageView') {
                    unset($this->paqs[$offset]);
                    break;
                }
            }
        }
    }

    public function piwikCode()
    {
        if ($this->disabled) {
            return '<!-- Piwik is disabled due to webfactory_piwik.disabled=true in your configuration -->';
        }

        $paq = json_encode($this->paqs);

        $piwikCode = <<<EOT
<!-- Piwik -->
<script type="text/javascript">//<![CDATA[
var _paq = (_paq||[]).concat({$paq});

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
