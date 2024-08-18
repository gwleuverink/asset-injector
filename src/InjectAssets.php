<?php

namespace Leuverink\AssetInjector;

use Leuverink\AssetInjector\Contracts\AssetInjector;
use Illuminate\Foundation\Http\Events\RequestHandled;

class InjectAssets
{
    public function __construct(
        protected AssetInjector $injector
    ) {}

    /** Injects assets inside every full-page response */
    public function __invoke(RequestHandled $handled)
    {
        if (! $this->injector->enabled()) {
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
        if (str_contains($html, '<!--[{$this->injector->identifier()}]-->')) {
            return;
        }

        // Keep a copy of the original response
        $originalContent = $handled->response->original;

        $handled->response->setContent(
            $this->inject($html, <<< HTML
            <!--[{$this->injector->identifier()}]-->
            {$this->injector->inject()}
            <!--[END{$this->injector->identifier()}]-->
            HTML)
        );

        $handled->response->original = $originalContent;
    }

    /** Injects assets into given html string (taken from Livewire's injection mechanism) */
    protected function inject(string $html, string $assets): string
    {
        $html = str($html);
        $assets = PHP_EOL . $assets . PHP_EOL;

        if ($html->test('/<\s*\/\s*head\s*>/i')) {
            return $html
                ->replaceMatches('/(<\s*\\s*head\s*>)/i', '$1' . $assets)
                ->toString();
        }

        return $html
            ->replaceMatches('/(<\s*html(?:\s[^>])*>)/i', '$1' . $assets)
            ->toString();
    }
}
