<?php

namespace StatamicCap\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Support\Facades\File;

class PublishWasm extends Command
{
    protected $signature = 'cap:publish-wasm';

    protected $description = 'Télécharge le WASM Cap et patche cap-widget.js pour un chargement local (CSP strict)';

    public function handle(Http $http): int
    {
        $jsPath = public_path('vendor/cap/cap-widget.js');

        if (! File::exists($jsPath)) {
            $this->error('cap-widget.js introuvable. Lancez d\'abord : php artisan vendor:publish --tag=cap-assets');

            return Command::FAILURE;
        }

        $js = File::get($jsPath);

        if (! preg_match('/"(https:\/\/cdn\.jsdelivr\.net\/[^"]+\.wasm)"/', $js, $matches)) {
            $this->error('URL WASM introuvable dans cap-widget.js. Le widget a peut-être déjà été patché.');

            return Command::FAILURE;
        }

        $wasmCdnUrl  = $matches[1];
        $wasmLocal   = public_path('vendor/cap/cap_wasm_bg.wasm');

        $this->info("Téléchargement du WASM depuis {$wasmCdnUrl}…");

        $response = $http->get($wasmCdnUrl);

        if ($response->failed()) {
            $this->error("Échec du téléchargement ({$response->status()}).");

            return Command::FAILURE;
        }

        File::put($wasmLocal, $response->body());
        $this->info('WASM enregistré dans ' . $wasmLocal);

        // Patche le JS : remplace l'URL hardcodée par window.CAP_WASM_URL avec fallback CDN
        $patched = str_replace(
            '"' . $wasmCdnUrl . '"',
            '(window.CAP_WASM_URL||"' . $wasmCdnUrl . '")',
            $js
        );

        File::put($jsPath, $patched);
        $this->info('cap-widget.js patché pour lire window.CAP_WASM_URL.');

        $this->newLine();
        $this->line('Le tag <b>{{ cap:scripts }}</b> injecte automatiquement <b>window.CAP_WASM_URL</b>');
        $this->line('dès que <b>public/vendor/cap/cap_wasm_bg.wasm</b> est présent.');
        $this->line('Ajoutez <b>connect-src \'self\'</b> à votre CSP — plus besoin de whitelister jsDelivr.');

        return Command::SUCCESS;
    }
}
