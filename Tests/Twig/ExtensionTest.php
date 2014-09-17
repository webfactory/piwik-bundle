<?php

namespace Twig;

use Webfactory\Bundle\PiwikBundle\Twig\Extension;

class ExtensionTest extends \PHPUnit_Framework_TestCase
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

    public function testPiwikCodeContainsHostName()
    {
        $hostname = 'myHost.de';
        $extension = new Extension(false, 1, $hostname, false);
        $this->assertContains($hostname, $extension->piwikCode());
    }

    public function testIsTwigExtension()
    {
        $extension = new Extension(false, 1, '', false);
        $this->assertInstanceOf('\Twig_ExtensionInterface', $extension);
    }

}
