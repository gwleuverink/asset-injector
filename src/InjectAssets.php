<?php

namespace Leuverink\InjectAssets;

use Illuminate\Foundation\Http\Events\RequestHandled;

class InjectAssets
{
    /** Injects a inline style tag containing MagicTodo's CSS inside every full-page response */
    public function __invoke(RequestHandled $handled)
    {
        // No need to inject anything when MagicTodo is disabled
        if (! config('magic-todo.enabled')) {
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
        if (str_contains($html, '<!--[MAGIC_TODO-ASSETS]-->')) {
            return;
        }

        // Keep a copy of the original response
        $originalContent = $handled->response->original;

        // Inject the assets in the response
        $js = file_get_contents(__DIR__ . '/../build/magic-todo.js');
        $css = file_get_contents(__DIR__ . '/../build/magic-todo.css');

        $handled->response->setContent(
            $this->injectAssets($html, <<< HTML
            <!--[MAGIC_TODO-ASSETS]-->
            <script type="module">{$js}</script>
            <style>{$css}</style>
            <!--[ENDMAGIC_TODO]-->
            HTML)
        );

        $handled->response->original = $originalContent;
    }

    /** Injects MagicTodo assets into given html string (taken from Livewire's injection mechanism) */
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
