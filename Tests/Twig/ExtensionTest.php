<?php

namespace Twig;

use PHPUnit\Framework\TestCase;
use Webfactory\Bundle\PiwikBundle\Twig\Extension;

class ExtensionTest extends TestCase
{
    public function testPiwikCodeReturnsNoScriptWhenDisabled()
    {
        $extension = new Extension(true, 1, '', false, true);
        $this->assertNotContains('script', $extension->piwikCode());
    }

    public function testPiwikCodeReturnsScript()
    {
        $extension = new Extension(false, 1, '', false, true);
        $this->assertContains('script', $extension->piwikCode());
    }

    public function testPiwikCodeContainsSiteId()
    {
        $siteId = 1234;
        $extension = new Extension(false, $siteId, '', false, true);
        $this->assertContains((string) $siteId, $extension->piwikCode());
    }

    public function testPiwikCodeContainsApiURL()
    {
        $extension = new Extension(false, 1, 'my.host', '/js/', true);
        $this->assertContains('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeDoesNotContainApiURL()
    {
        $siteId = 1;
        $hostname = 'myHost.de';
        $path = 'piwik.php';
        $extension = new Extension(false, $siteId, $hostname, $path, true);
        $this->assertNotContains('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeContainsHostName()
    {
        $hostname = 'myHost.de';
        $extension = new Extension(false, 1, $hostname, false, true);
        $this->assertContains($hostname, $extension->piwikCode());
    }

    public function testAdditionalApiCallsCanBeAdded()
    {
        $extension = new Extension(false, 1, 'my.host', false, true);
        $extension->piwikPush('foo', 'bar', 'baz');
        $this->assertContains('["foo","bar","baz"]', $extension->piwikCode());
    }

    public function testTrackPageViewEnabledByDefault()
    {
        $extension = new Extension(false, 1, 'my.host', false, true);
        $this->assertContains('"trackPageView"', $extension->piwikCode());
    }

    public function testCookiesDisabledByDefault()
    {
        $extension = new Extension(false, 1, 'my.host', false, true);
        $this->assertContains('"disableCookies"', $extension->piwikCode());
    }

    public function testTrackSiteSearchDisablesPageTracking()
    {
        $extension = new Extension(false, 1, 'my.host', false, true);
        $extension->piwikPush('trackSiteSearch', 'Banana', 'Organic Food', 42);

        $code = $extension->piwikCode();
        $this->assertContains('"trackSiteSearch"', $code);
        $this->assertNotContains('"trackPageView"', $code);
    }

    public function testIsTwigExtension()
    {
        $extension = new Extension(false, 1, '', false, true);
        $this->assertInstanceOf('\Twig_ExtensionInterface', $extension);
    }
}
