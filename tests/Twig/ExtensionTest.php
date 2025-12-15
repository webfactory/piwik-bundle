<?php

namespace Webfactory\Bundle\PiwikBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Twig\Extension\ExtensionInterface;
use Webfactory\Bundle\PiwikBundle\Twig\Extension;

class ExtensionTest extends TestCase
{
    public function testPiwikCodeReturnsNoScriptWhenDisabled(): void
    {
        $extension = new Extension(true, 1, '', false);
        self::assertStringNotContainsString('script', $extension->piwikCode());
    }

    public function testPiwikCodeReturnsScript(): void
    {
        $extension = new Extension(false, 1, '', false);
        self::assertStringContainsString('script', $extension->piwikCode());
    }

    public function testPiwikCodeContainsSiteId(): void
    {
        $siteId = 1234;
        $extension = new Extension(false, $siteId, '', false);
        self::assertStringContainsString((string) $siteId, $extension->piwikCode());
    }

    public function testPiwikCodeContainsApiURL(): void
    {
        $extension = new Extension(false, 1, 'my.host', '/js/');
        self::assertStringContainsString('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeDoesNotContainApiURL(): void
    {
        $siteId = 1;
        $hostname = 'myHost.de';
        $path = 'piwik.php';
        $extension = new Extension(false, $siteId, $hostname, $path);
        self::assertStringNotContainsString('_paq.push([\'setAPIUrl\', u]);', $extension->piwikCode());
    }

    public function testPiwikCodeContainsHostName(): void
    {
        $hostname = 'myHost.de';
        $extension = new Extension(false, 1, $hostname, false);
        self::assertStringContainsString($hostname, $extension->piwikCode());
    }

    public function testPiwikOptOutCodeContainsHostName(): void
    {
        $hostname = 'myHost.de';
        $extension = new Extension(false, 1, $hostname, false);
        self::assertStringContainsString($hostname, $extension->piwikOptOutCode());
    }

    public function testAdditionalApiCallsCanBeAdded(): void
    {
        $extension = new Extension(false, 1, 'my.host', false);
        $extension->piwikPush('foo', 'bar', 'baz');
        self::assertStringContainsString('["foo","bar","baz"]', $extension->piwikCode());
    }

    public function testTrackPageViewEnabledByDefault(): void
    {
        $extension = new Extension(false, 1, 'my.host', false);
        self::assertStringContainsString('"trackPageView"', $extension->piwikCode());
    }

    public function testCookiesCanBeDisabled(): void
    {
        $extension = new Extension(false, 1, 'my.host', false);
        self::assertStringContainsString('"disableCookies"', $extension->piwikCode());
    }

    public function testCookiesCanBeEnabled(): void
    {
        $extension = new Extension(false, 1, 'my.host', false, false);
        self::assertStringNotContainsString('"disableCookies"', $extension->piwikCode());
    }

    public function testTrackSiteSearchDisablesPageTracking(): void
    {
        $extension = new Extension(false, 1, 'my.host', false);
        $extension->piwikPush('trackSiteSearch', 'Banana', 'Organic Food', 42);

        $code = $extension->piwikCode();
        self::assertStringContainsString('"trackSiteSearch"', $code);
        self::assertStringNotContainsString('"trackPageView"', $code);
    }

    public function testIsTwigExtension(): void
    {
        $extension = new Extension(false, 1, '', false);
        $this->assertInstanceOf(ExtensionInterface::class, $extension);
    }
}
