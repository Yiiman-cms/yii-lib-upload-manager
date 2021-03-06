<?php

namespace YiiMan\LibUploadManager\lib\imageManager;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Actual provider
     *
     * @var \Illuminate\Support\ServiceProvider
     */
    protected $provider;

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->provider = $this->getProvider();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if (method_exists($this->provider, 'boot')) {
            return $this->provider->boot();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        return $this->provider->register();
    }

    /**
     * Return ServiceProvider according to Laravel version
     *
     * @return \YiiMan\LibUploadManager\lib\imageManager\Provider\ProviderInterface
     */
    private function getProvider()
    {
        if ($this->app instanceof \Laravel\Lumen\Application) {
            $provider = '\YiiMan\LibUploadManager\lib\imageManager\ImageServiceProviderLumen';
        } elseif (version_compare(\Illuminate\Foundation\Application::VERSION, '5.0', '<')) {
            $provider = '\YiiMan\LibUploadManager\lib\imageManager\ImageServiceProviderLaravel4';
        } else {
            $provider = '\YiiMan\LibUploadManager\lib\imageManager\ImageServiceProviderLaravel5';
        }

        return new $provider($this->app);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['image'];
    }
}
