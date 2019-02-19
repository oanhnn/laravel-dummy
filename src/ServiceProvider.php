<?php

namespace Laravel\Dummy;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package     Laravel\Dummy
 * @author      Oanh Nguyen <oanhnn.bk@gmail.com>
 * @license     The MIT license
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // publishes
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__) . '/config/dummy.php' => base_path('config/dummy.php'),
            ], 'laravel-dummy-config');
        }

        // other booting ...
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // merges config
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/dummy.php', 'dummy');

        // register dummy class
        $this->app->singleton(Dummy::class, function ($app) {
            $config = $app['config']->get('dummy');
            $instance = new Dummy($config);

            return $instance;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Dummy::class,
        ];
    }
}
