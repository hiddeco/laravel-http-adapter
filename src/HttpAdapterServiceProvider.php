<?php

/*
 * This file is part of Laravel HTTP adapter.
 *
 * (c) Hidde Beydals <hello@hidde.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiddeCo\HttpAdapter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Ivory\HttpAdapter\Configuration;
use Ivory\HttpAdapter\HttpAdapterFactory as AdapterFactory;
use Ivory\HttpAdapter\HttpAdapterInterface;

class HttpAdapterServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfig();
    }


    /**
     * Merge the config files.
     *
     * @return void
     */
    protected function mergeConfig()
    {
        $src = realpath(__DIR__ . '/../config/httpadapter.php');

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([ $src => config_path('httpadapter.php') ]);
        }

        $this->mergeConfigFrom($src, 'httpadapter');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAdapterFactory($this->app);
        $this->registerConfigurationFactory($this->app);
        $this->registerHttpAdapterFactory($this->app);
        $this->registerManager($this->app);
        $this->registerBindings($this->app);
    }

    /**
     *
     */

    /**
     * Register the adapter factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerAdapterFactory(Application $app)
    {
        $app->singleton('httpadapter.adapterfactory', function () {
            return new AdapterFactory();
        });

        $app->alias('httpadapter.adapterfactory', AdapterFactory::class);
    }

    /**
     * Register the configuration factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerConfigurationFactory(Application $app)
    {
        $app->singleton('httpadapter.configurationfactory', function ($app) {
            $configuration = new Configuration();

            return new ConfigurationFactory($configuration);
        });

        $app->alias('httpadapter.configurationfactory', ConfigurationFactory::class);
    }

    /**
     * Register the HTTP adapter factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerHttpAdapterFactory(Application $app)
    {
        $app->singleton('httpadapter.factory', function ($app) {
            $adapter = $app['httpadapter.adapterfactory'];
            $configuration = $app['httpadapter.configurationfactory'];
            $eventDispatcher = $app->make('HiddeCo\HttpAdapter\Adapters\AbstractEventDispatcher');

            return new HttpAdapterFactory($adapter, $configuration, $eventDispatcher);
        });
    }

    /**
     * Register the manager class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerManager(Application $app)
    {
        $app->singleton('httpadapter', function ($app) {
            $config = $app['config'];
            $factory = $app['httpadapter.factory'];

            return new HttpAdapterManager($config, $factory);
        });

        $app->alias('httpadapter', HttpAdapterManager::class);
    }

    /**
     * Register the bindings.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerBindings(Application $app)
    {
        $app->bind(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface',
            'Symfony\Component\EventDispatcher\EventDispatcher'
        );

        $app->bind(
            'HiddeCo\HttpAdapter\Adapters\AbstractEventDispatcher',
            'HiddeCo\HttpAdapter\Adapters\EventDispatcherLaravel'
        );

        $app->bind('httpadapter.connection', function ($app) {
            $manager = $app['httpadapter'];

            return $manager->connection();
        });

        $app->alias('httpadapter.connection', HttpAdapterInterface::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'httpadapter.adapterfactory',
            'httpadapter.configurationfactory',
            'httpadapter.factory',
            'httpadapter',
            'httpadapter.connection'
        ];
    }
}
