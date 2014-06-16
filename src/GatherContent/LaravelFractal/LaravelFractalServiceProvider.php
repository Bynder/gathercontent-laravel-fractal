<?php namespace GatherContent\LaravelFractal;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;

class LaravelFractalServiceProvider extends ServiceProvider
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
		$this->package('gathercontent/laravel-fractal', 'fractal');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('fractal', function ($app) {
            $inputkey = $app['config']->get('fractal::include_key');
            $includes = $app['request']->input($inputkey);
            
            $manager = new Manager;

            if ($includes) {
                $manager->parseIncludes($includes);
            }
            
            return new LaravelFractalService($manager);
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('fractal');
	}
}
