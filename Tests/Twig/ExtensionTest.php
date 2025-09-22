<?php

namespace Twig;

use PHPUnit\Framework\TestCase;
use Webfactory\Bundle\PiwikBundle\Twig\Extension;

class ExtensionTest extends TestCase
{
    public function testPiwikCodeReturnsNoScriptWhenDisabled()
    {
        $extension = new Extension(true, 1, '', false);
        self::assertStringNotContainsString('script', $extension->piwikCode());
    }

    public function testPiwikCodeReturnsScript()
    {
        $extension = new Extension(false, 1, '', false);
        self::assertStringContainsString('script', $extension->piwikCode());
    }

    public function testPiwikCodeContainsSiteId()
    {
        $siteId = 1234;
        $extension = new Extension(false, $siteId, '', false);
        self::assertStringContainsString((string) $siteId, $extension->piwikCode());
    }

    public function testPiwikCodeContainsApiURL()
    {
        $extension = new Extension(false, 1, 'my.host', '/js/');
        self::assertStringContainsString('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeDoesNotContainApiURL()
    {
        $siteId = 1;
        $hostname = 'myHost.de';
        $path = 'piwik.php';
        $extension = new Extension(false, $siteId, $hostname, $path);
        self::assertStringNotContainsString('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeContainsHostName()
    {
        $hostname = 'myHost.de';
        $extension = new Extension(false, 1, $hostname, false);
        self::assertStringContainsString($hostname, $extension->piwikCode());
    }

    public function testPiwikOptOutCodeContainsHostName()
    {
        $hostname = 'myHost.de';
        $extension = new Extension(false, 1, $hostname, false);
        self::assertStringContainsString($hostname, $extension->piwikOptOutCode());
    }

    public function testAdditionalApiCallsCanBeAdded()
    {
        $extension = new Extension(false, 1, 'my.host', false);
        $extension->piwikPush('foo', 'bar', 'baz');
        self::assertStringContainsString('["foo","bar","baz"]', $extension->piwikCode());
    }

    public function testTrackPageViewEnabledByDefault()
    {
        $extension = new Extension(false, 1, 'my.host', false);
        self::assertStringContainsString('"trackPageView"', $extension->piwikCode());
    }

    public function testCookiesCanBeDisabled()
    {
        $extension = new Extension(false, 1, 'my.host', false);
        self::assertStringContainsString('"disableCookies"', $extension->piwikCode());
    }

    public function testCookiesCanBeEnabled()
    {
        $extension = new Extension(false, 1, 'my.host', false, false);
        self::assertStringNotContainsString('"disableCookies"', $extension->piwikCode());
    }

    public function testTrackSiteSearchDisablesPageTracking()
    {
        $extension = new Extension(false, 1, 'my.host', false);
        $extension->piwikPush('trackSiteSearch', 'Banana', 'Organic Food', 42);

        $code = $extension->piwikCode();
        self::assertStringContainsString('"trackSiteSearch"', $code);
        self::assertStringNotContainsString('"trackPageView"', $code);
    }

    public function testIsTwigExtension()
    {
        $extension = new Extension(false, 1, '', false);
        $this->assertInstanceOf(\Twig\Extension\ExtensionInterface::class, $extension);
    }
}
