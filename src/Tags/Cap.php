<?php

namespace StatamicCap\Tags;

use Statamic\Tags\Tags;

class Cap extends Tags
{
    protected static $handle = 'cap';

    /**
     * {{ cap }}
     * Renders the Cap widget HTML element.
     */
    public function index(): string
    {
        $endpoint = config('statamic-cap.endpoint', config('cap.endpoint'));
        $nonce = $this->params->get('nonce');

        $attrs = 'data-cap-api-endpoint="' . e($endpoint) . '"';

        if ($nonce) {
            $attrs .= ' data-cap-csp-nonce="' . e($nonce) . '"';
        }

        return '<cap-widget ' . $attrs . '></cap-widget>';
    }

    /**
     * {{ cap:scripts }}
     * Renders the Cap JS script tag.
     */
    public function scripts(): string
    {
        $nonce = $this->params->get('nonce');
        $src = e(asset('vendor/cap/cap-widget.js'));

        if ($nonce) {
            return '<script type="module" nonce="' . e($nonce) . '" src="' . $src . '"></script>';
        }

        return '<script type="module" src="' . $src . '"></script>';
    }

    /**
     * {{ cap:styles }}
     * Renders the Cap CSS link tag.
     */
    public function styles(): string
    {
        return '<link rel="stylesheet" href="' . e(asset('vendor/cap/cap-widget.css')) . '">';
    }
}
