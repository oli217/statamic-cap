<?php

namespace StatamicCap\Tags;

use Statamic\Tags\Tags;

class Cap extends Tags
{
    protected static $handle = 'cap';

    /**
     * {{ cap }}
     * Renders the Cap widget HTML element.
     *
     * Note: the nonce for inline styles is set via window.CAP_CSS_NONCE
     * in {{ cap:scripts }}, not via a data attribute.
     */
    public function index(): string
    {
        $endpoint = config('statamic-cap.endpoint', config('cap.endpoint'));

        return '<cap-widget data-cap-api-endpoint="' . e($endpoint) . '"></cap-widget>';
    }

    /**
     * {{ cap:scripts }}
     * {{ cap:scripts nonce="x" }}
     *
     * Injects:
     *   - window.CAP_CSP_NONCE / window.CAP_CSS_NONCE  (when nonce provided)
     *   - window.CAP_WASM_URL                           (when local WASM is published)
     *   - <script type="module"> for cap-widget.js
     */
    public function scripts(): string
    {
        $nonce    = $this->params->get('nonce');
        $src      = e(asset('vendor/cap/cap-widget.js'));
        $nonceAttr = $nonce ? ' nonce="' . e($nonce) . '"' : '';

        $globals = $this->buildGlobals($nonce);

        $output = '';

        if ($globals !== '') {
            $output .= '<script' . $nonceAttr . '>' . $globals . '</script>' . "\n";
        }

        $output .= '<script type="module"' . $nonceAttr . ' src="' . $src . '"></script>';

        return $output;
    }

    /**
     * {{ cap:styles }}
     * Renders the Cap CSS link tag.
     */
    public function styles(): string
    {
        return '<link rel="stylesheet" href="' . e(asset('vendor/cap/cap-widget.css')) . '">';
    }

    private function buildGlobals(?string $nonce): string
    {
        $assignments = [];

        if ($nonce) {
            $encoded = json_encode($nonce);
            $assignments[] = 'window.CAP_CSP_NONCE=' . $encoded;
            $assignments[] = 'window.CAP_CSS_NONCE=' . $encoded;
        }

        if (file_exists(public_path('vendor/cap/cap_wasm_bg.wasm'))) {
            $assignments[] = 'window.CAP_WASM_URL=' . json_encode(asset('vendor/cap/cap_wasm_bg.wasm'));
        }

        return implode(';', $assignments);
    }
}
