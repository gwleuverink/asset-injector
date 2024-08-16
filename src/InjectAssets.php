<?php

namespace Leuverink\InjectAssets;

use Leuverink\InjectAssets\Contracts\AssetInjector;
use Illuminate\Foundation\Http\Events\RequestHandled;

class InjectAssets
{
    /** Injects assets inside every full-page response */
    public function __invoke(RequestHandled $handled)
    {
        $injector = $this->resolveInjector();

        if (! $injector->enabled()) {
            return;
        }

        if (! $handled->response->isSuccessful()) {
            return;
        }

        $html = $handled->response->getContent();

        // Skip if request doesn't return a full page
        if (! str_contains($html, '</html>')) {
            return;
        }

        // Skip if core was included before
        if (str_contains($html, '<!--[{$injector->identifier()} ASSETS]-->')) {
            return;
        }

        // Keep a copy of the original response
        $originalContent = $handled->response->original;

        $handled->response->setContent(
            $this->injectAssets($html, <<< HTML
            <!--[{$injector->identifier()} ASSETS]-->
            {$injector->inject()}
            <!--[END{$injector->identifier()}]-->
            HTML)
        );

        $handled->response->original = $originalContent;
    }

    protected function resolveInjector(): AssetInjector
    {
        return resolve(AssetInjector::class);
    }

    /** Injects assets into given html string (taken from Livewire's injection mechanism) */
    protected function injectAssets(string $html, string $core): string
    {
        $html = str($html);

        if ($html->test('/<\s*\/\s*head\s*>/i')) {
            return $html
                ->replaceMatches('/(<\s*\\s*head\s*>)/i', '$1' . $core)
                ->toString();
        }

        return $html
            ->replaceMatches('/(<\s*html(?:\s[^>])*>)/i', '$1' . $core)
            ->toString();
    }
}
