<?php

namespace StatamicCap;

use Illuminate\Support\Facades\Route;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\YAML;
use Statamic\Providers\AddonServiceProvider;
use StatamicCap\Console\Commands\PublishWasm;
use StatamicCap\Http\Controllers\SettingsController;
use StatamicCap\Listeners\ValidateCapToken;
use StatamicCap\Tags\Cap;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Cap::class,
    ];

    protected $commands = [
        PublishWasm::class,
    ];

    protected $listen = [
        \Statamic\Events\FormSubmitted::class => [
            ValidateCapToken::class,
        ],
    ];

    public function bootAddon(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/statamic-cap.php', 'statamic-cap');
        $this->loadSettingsFromYaml();
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'statamic-cap');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'statamic-cap');

        $this->bootCpRoutes();
        $this->bootCpNav();

        $this->publishes([
            __DIR__ . '/../config/statamic-cap.php' => config_path('statamic-cap.php'),
        ], 'statamic-cap-config');
    }

    private function loadSettingsFromYaml(): void
    {
        $path = storage_path('statamic/addons/statamic-cap.yaml');

        if (! file_exists($path)) {
            return;
        }

        $settings = YAML::file($path)->parse();

        config(['statamic-cap' => array_merge(config('statamic-cap', []), $settings)]);
    }

    private function bootCpRoutes(): void
    {
        $this->registerCpRoutes(function () {
            Route::get('statamic-cap/settings', [SettingsController::class, 'edit'])
                ->name('statamic-cap.settings');
            Route::post('statamic-cap/settings', [SettingsController::class, 'update'])
                ->name('statamic-cap.settings.update');
        });
    }

    private function bootCpNav(): void
    {
        Nav::extend(function ($nav) {
            $nav->create('Cap CAPTCHA')
                ->section('Tools')
                ->url(cp_route('statamic-cap.settings'))
                ->icon('settings-sliders');
        });
    }
}
