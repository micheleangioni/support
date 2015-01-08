<?php namespace MicheleAngioni\Support;

use Illuminate\Support\ServiceProvider;
use App;

class SupportServiceProvider extends ServiceProvider {

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
		$this->package('michele-angioni/support');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->registerHelpers();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}


    public function registerHelpers()
    {
        App::bind('helpers', function()
        {
            return new Helpers;
        });
    }

}
