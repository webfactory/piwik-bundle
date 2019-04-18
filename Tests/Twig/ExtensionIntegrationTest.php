<?php

namespace Twig;

use PHPUnit\Framework\TestCase;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\ArrayLoader;
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
        $siteId = 1;
        $hostname = 'myHost.de';

        $output = $this->renderWithExtension('{{ piwik_code() }}', new Extension(false, $siteId, $hostname, false));

        $this->assertContains((string) $siteId, $output);
        $this->assertContains($hostname, $output);
    }

    public function testCustomApiCallsThroughPiwikFunction()
    {
        $output = $this->renderWithExtension("
            {{ piwik('foo', 'bar', 'baz') }}
            {{ piwik_code() }}
        ", new Extension(false, null, null, false));

        $this->assertContains('["foo","bar","baz"]', $output);
    }

    private function renderWithExtension($templateString, ExtensionInterface $extension)
    {
        $twig = new Environment(
            new ArrayLoader(),
            ['debug' => true]
        );

        $twig->addExtension($extension);

        $template = $twig->createTemplate($templateString);

        return $template->render([]);
    }
}
