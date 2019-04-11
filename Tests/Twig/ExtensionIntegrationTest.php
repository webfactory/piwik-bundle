<?php

namespace Twig;

use PHPUnit\Framework\TestCase;
use Webfactory\Bundle\PiwikBundle\Twig\Extension;

/**
 * Integration tests for Extension.
 */
final class ExtensionIntegrationTest extends TestCase
{
    /**
     * Ensures '{{ piwik_code() }}' can be parsed by a Twig environment and it's transformation contains essential bits.
     */
    public function testExpressionGetsTransformedByTwigEnvironment()
    {
        $twig = $this->getTestTwigEnvironement();
        $siteId = 1;
        $hostname = 'myHost.de';
        $twig->addExtension(new Extension(false, $siteId, $hostname, false));

        $output = $twig->createTemplate('{{ piwik_code() }}')->render([]);

        $this->assertContains((string) $siteId, $output);
        $this->assertContains($hostname, $output);
    }

    public function testCustomApiCallsThroughPiwikFunction()
    {
        $twig = $this->getTestTwigEnvironement();

        $twig->addExtension(new Extension(false, null, null, false));

        $output = $twig->createTemplate("
            {{ piwik('foo', 'bar', 'baz') }}
            {{ piwik_code() }}
        ")->render([]);

        $this->assertContains('["foo","bar","baz"]', $output);
    }

    /**
     * instead of the obsolete
     * new \Twig_Environment(
     *      array('debug' => true, 'cache' => false, 'autoescape' => true, 'optimizations' => 0)
     * );.
     *
     * @return \Twig_Environment
     */
    protected function getTestTwigEnvironement()
    {
        $twig = new \Twig_Environment(new \Twig_Loader_Array([]));
        $twig->setCache(false);

        return $twig;
    }
}
