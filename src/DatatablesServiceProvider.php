<?php

namespace dubroquin\vuetable;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;

/**
 * Class vuetableServiceProvider.
 *
 * @package dubroquin\vuetable\;
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class VuetableServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/vuetable.php', 'vuetable');

        $this->publishes([
            __DIR__ . '/config/vuetable.php' => config_path('vuetable.php'),
        ], 'vuetable');

        // Publish Vue.js files
        $this->publishes([
            __DIR__.'/resources/' => resource_path('/assets/js/components/commons')
        ], 'vuetable');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->isLumen()) {
            require_once 'fallback.php';
        }

        $this->app->singleton('vuetable.fractal', function () {
            $fractal = new Manager;
            $config  = $this->app['config'];
            $request = $this->app['request'];

            $includesKey = $config->get('vuetable.fractal.includes', 'include');
            if ($request->get($includesKey)) {
                $fractal->parseIncludes($request->get($includesKey));
            }

            $serializer = $config->get('vuetable.fractal.serializer', DataArraySerializer::class);
            $fractal->setSerializer(new $serializer);

            return $fractal;
        });

        $this->app->alias('vuetable', Vuetable::class);
        $this->app->singleton('vuetable', function () {
            return new vuetable(new Request(app('request')));
        });

        $this->registerAliases();
    }

    /**
     * Check if app uses Lumen.
     *
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }

    /**
     * Create aliases for the dependency.
     */
    protected function registerAliases()
    {
        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Vuetable', \dubroquin\vuetable\Facades\Vuetable::class);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return ['vuetable', 'vuetable.fractal'];
    }
}
