<?php

namespace Twig;

use Webfactory\Bundle\PiwikBundle\Twig\Extension;

/**
 * Integration tests for Extension.
 */
final class ExtensionIntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures '{{ piwik_code() }}' can be parsed by a Twig environment and it's transformation contains essential bits.
     */
    public function testExpressionGetsTransformedByTwigEnvironment()
    {
        $twig = new \Twig_Environment(
            new \Twig_Loader_String(),
            array('debug' => true, 'cache' => false, 'autoescape' => true, 'optimizations' => 0)
        );
        $siteId = 1;
        $hostname = 'myHost.de';
        $twig->addExtension(new Extension(false, $siteId, $hostname, false));

        $output = $twig->render('{{ piwik_code() }}');

        $this->assertContains((string)$siteId, $output);
        $this->assertContains($hostname, $output);
    }
}
