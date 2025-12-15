<?php

namespace Webfactory\Bundle\PiwikBundle\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
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
    public function testExpressionGetsTransformedByTwigEnvironment(): void
    {
        $siteId = 1;
        $hostname = 'myHost.de';

        $output = $this->renderWithExtension('{{ piwik_code() }}', new Extension(false, $siteId, $hostname, false));

        self::assertStringContainsString((string) $siteId, $output);
        self::assertStringContainsString($hostname, $output);
    }

    public function testCustomApiCallsThroughPiwikFunction(): void
    {
        $output = $this->renderWithExtension("
            {{ piwik('foo', 'bar', 'baz') }}
            {{ piwik_code() }}
        ", new Extension(false, 1, 'my.host', false));

        self::assertStringContainsString('["foo","bar","baz"]', $output);
    }

    private function renderWithExtension($templateString, ExtensionInterface $extension): string
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
