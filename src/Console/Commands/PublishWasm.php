<?php

namespace StatamicCap\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Factory as Http;
use Illuminate\Support\Facades\File;

class PublishWasm extends Command
{
    protected $signature = 'cap:publish-wasm';

    protected $description = 'Télécharge le WASM Cap en local pour un chargement auto-hébergé (CSP strict)';

    public function handle(Http $http): int
    {
        $jsPath = base_path('vendor/oliweb/laravel-cap/resources/js/cap-widget.js');

        if (! File::exists($jsPath)) {
            $this->error('cap-widget.js introuvable dans vendor/oliweb/laravel-cap. Vérifiez votre installation Composer.');

            return Command::FAILURE;
        }

        $js = File::get($jsPath);

        if (! preg_match('/"(https:\/\/cdn\.jsdelivr\.net\/[^"]+\.wasm)"/', $js, $matches)) {
            $this->error('URL WASM introuvable dans cap-widget.js.');

            return Command::FAILURE;
        }

        $wasmCdnUrl = $matches[1];
        $wasmDir    = storage_path('app/statamic-cap');
        $wasmLocal  = $wasmDir . '/cap_wasm_bg.wasm';

        $this->info("Téléchargement du WASM depuis {$wasmCdnUrl}…");

        $response = $http->get($wasmCdnUrl);

        if ($response->failed()) {
            $this->error("Échec du téléchargement ({$response->status()}).");

            return Command::FAILURE;
        }

        File::ensureDirectoryExists($wasmDir);
        File::put($wasmLocal, $response->body());

        $this->info('WASM enregistré dans ' . $wasmLocal);
        $this->newLine();
        $this->line('Le tag <b>{{ cap:scripts }}</b> injecte automatiquement <b>window.CAP_CUSTOM_WASM_URL</b>');
        $this->line('pointant vers la route <b>/vendor/statamic-cap/cap_wasm_bg.wasm</b>.');
        $this->line('Ajoutez <b>connect-src \'self\'</b> à votre CSP — plus besoin de whitelister jsDelivr.');

        return Command::SUCCESS;
    }
}
