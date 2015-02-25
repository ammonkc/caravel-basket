<?php namespace Ammonkc\Basket;

use Illuminate\Support\ServiceProvider;

class BasketServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/basket.php';
        $this->publishes([$configPath => config_path('basket.php')], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/basket.php';
        $this->mergeConfigFrom($configPath, 'basket');

        $this->registerSession();

        $this->registerBasket();
    }

    /**
     * Register service provider bindings
     */
    public function registerBasket()
    {
        $this->app['basket'] = $this->app->share(function($app)
        {
            $storage      = $app['session'];
            $events       = $app['events'];
            $instanceName = 'basket';
            $session_key  = '4yTlTDKu3oJOfzDA';

            return new Basket($storage, $events, $instanceName, $session_key);
        });
    }

    /**
     * Registers the session.
     *
     * @return void
     */
    protected function registerSession()
    {
        $this->app['Ammonkc\Basket\Storage\StorageInterface'] = $this->app->share(function ($app) {
            return new IlluminateSession($app['session.store']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('basket');
    }

}
