<?php

namespace Webfactory\Bundle\PiwikBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    /**
     * @var bool
     */
    private $disabled;

    /**
     * @var int
     */
    private $siteId;

    /**
     * @var string
     */
    private $piwikHost;

    /**
     * @var string
     */
    private $trackerPath;

    /**
     * @var bool
     */
    private $disableCookies;

    /**
     * @var array
     */
    private $paqs = [];

    public function __construct(bool $disabled, string $siteId, string $piwikHost, string $trackerPath, bool $disableCookies = false)
    {
        $this->disabled = $disabled;
        $this->siteId = $siteId;
        $this->piwikHost = rtrim($piwikHost, '/');
        $this->trackerPath = ltrim($trackerPath, '/');
        $this->disableCookies = $disableCookies;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('piwik_code', [$this, 'piwikCode'], ['is_safe' => ['html']]),
            new TwigFunction('piwik', [$this, 'piwikPush']),
        ];
    }

    public function piwikPush(...$paqs)
    {
        $this->paqs[] = $paqs;
    }

    public function piwikCode()
    {
        if ($this->disabled) {
            return '<!-- Piwik is disabled due to webfactory_piwik.disabled=true in your configuration -->';
        }

        /*
         * https://matomo.org/faq/general/faq_157/
         * Call disableCookies before calling trackPageView
         */
        if ($this->disableCookies === true) {
            $this->paqs[] = ['disableCookies'];
        }

        $this->addDefaultApiCalls();

        $paq = json_encode($this->paqs);

        $piwikCode = <<<EOT
<!-- Piwik -->
<script type="text/javascript">//<![CDATA[
var _paq = (_paq||[]).concat({$paq});
_paq.push(["setDoNotTrack", true]);
(function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://{$this->piwikHost}/";
    _paq.push(["setTrackerUrl", u+"{$this->trackerPath}"]);
    _paq.push(["setSiteId", "{$this->siteId}"]);
EOT;
        if ('piwik.php' !== $this->trackerPath) {
            $piwikCode .= <<<EOT
    _paq.push(['setAPIUrl', u]);
EOT;
        }
        $piwikCode .= <<<EOT
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"{$this->trackerPath}"; s.parentNode.insertBefore(g,s);
})();
//]]></script>
<noscript><p><img src="//{$this->piwikHost}/piwik.php?idsite={$this->siteId}&amp;rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
EOT;

        return $piwikCode;
    }

    private function addDefaultApiCalls()
    {
        $this->paqs[] = ['enableLinkTracking'];

        foreach ($this->paqs as $paq) {
            if ('trackSiteSearch' === $paq[0]) {
                /*
                 * It is recommended *not* to "trackPageView" for "trackSiteSearch" pages.
                 * See http://developer.piwik.org/api-reference/tracking-javascript#tracking-internal-search-keywords-categories-and-no-result-search-keywords
                 * or http://piwik.org/docs/site-search/#track-site-search-using-the-javascript-tracksitesearch-function.
                 */
                return; // Do not add 'trackPageView'
            }
        }

        $this->paqs[] = ['trackPageView'];
    }
}
