<?php

namespace Twig;

use Webfactory\Bundle\PiwikBundle\Twig\Extension;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{

    public function testPiwikCodeReturnsNoScriptWhenDisabled()
    {
        $extension = new Extension(true, 1, '', false);
        $this->assertNotContains('script', $extension->piwikCode());
    }

    public function testPiwikCodeReturnsScript()
    {
        $extension = new Extension(false, 1, '', false);
        $this->assertContains('script', $extension->piwikCode());
    }

    public function testPiwikCodeContainsSiteId()
    {
        $siteId = 1234;
        $extension = new Extension(false, $siteId, '', false);
        $this->assertContains((string)$siteId, $extension->piwikCode());
    }

    public function testPiwikCodeContainsApiURL() {
        $siteId = 1;
        $hostname = 'myHost.de';
        $path = '/js/';
        $extension = new Extension(false, $siteId, $hostname, $path);
        $this->assertContains('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeDoesNotContainApiURL() {
        $siteId = 1;
        $hostname = 'myHost.de';
        $path = 'piwik.php';
        $extension = new Extension(false, $siteId, $hostname, $path);
        $this->assertNotContains('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeContainsHostName()
    {
        $hostname = 'myHost.de';
        $extension = new Extension(false, 1, $hostname, false);
        $this->assertContains($hostname, $extension->piwikCode());
    }

    public function testAdditionalApiCallsCanBeAdded()
    {
        $extension = new Extension(false, null, null, false);
        $extension->piwikPush("foo", "bar", "baz");
        $this->assertContains('["foo","bar","baz"]', $extension->piwikCode());
    }

    public function testTrackPageViewEnabledByDefault()
    {
        $extension = new Extension(false, null, null, false);
        $this->assertContains('"trackPageView"', $extension->piwikCode());
    }

    public function testTrackSiteSearchDisablesPageTracking()
    {
        $extension = new Extension(false, null, null, false);
        $extension->piwikPush('trackSiteSearch', "Banana", "Organic Food", 42);

        $code = $extension->piwikCode();
        $this->assertContains('"trackSiteSearch"', $code);
        $this->assertNotContains('"trackPageView"', $code);
    }

    public function testIsTwigExtension()
    {
        $extension = new Extension(false, 1, '', false);
        $this->assertInstanceOf('\Twig_ExtensionInterface', $extension);
    }

}
