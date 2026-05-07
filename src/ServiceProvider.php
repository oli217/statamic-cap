<?php

namespace StatamicCap;

use Edalzell\Forma\Forma;
use Statamic\Providers\AddonServiceProvider;
use StatamicCap\Listeners\ValidateCapToken;
use StatamicCap\Tags\Cap;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Cap::class,
    ];

    protected $listen = [
        \Statamic\Events\FormSubmitted::class => [
            ValidateCapToken::class,
        ],
    ];

    public function bootAddon(): void
    {
        Forma::add('oliweb/statamic-cap');

        $this->mergeConfigFrom(__DIR__ . '/../config/statamic-cap.php', 'statamic-cap');

        $this->publishes([
            __DIR__ . '/../config/statamic-cap.php' => config_path('statamic-cap.php'),
        ], 'statamic-cap-config');
    }
}
