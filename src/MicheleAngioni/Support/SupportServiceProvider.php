<?php namespace MicheleAngioni\Support;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Factory as ValidationFactory;

class SupportServiceProvider extends ServiceProvider
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
		// Publish config files
		$this->publishes([
			__DIR__ . '/../../config/config.php' => config_path('ma_support.php'),
		]);

		$this->mergeConfigFrom(
			__DIR__ . '/../../config/config.php', 'ma_support'
		);

		$this->registerCustomValidators();
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
		return [];
	}


	public function registerHelpers()
	{
		$this->app->bind('helpers', function () {
			return new Helpers;
		});
	}

	public function registerCustomValidators()
	{
		$validator = $this->app->make('Illuminate\Validation\Factory');

		$validator->resolver(function ($translator, $data, $rules, $messages) {
			$messages = [
				'alpha_complete' => 'Only the following characters are allowed: alphabetic, numbers, spaces, slashes and several punctuation characters.',
				'alpha_space' => 'Only the following characters are allowed: alphabetic, numbers and spaces.',
				'alpha_underscore' => 'Only the following characters are allowed: alphabetic, numbers and underscores.',
				'alphanumeric_names' => 'Only the following characters are allowed: letters, numbers, menus, apostrophes, underscores and spaces.',
				'alphanumeric_dotted_names' => 'Only the following characters are allowed: letters, numbers, menus, apostrophes, underscores, dots and spaces.',
				'alpha_names' => 'Only the following characters are allowed: alphabetic, menus, apostrophes, underscores and spaces.',
			];

			return new CustomValidators($translator, $data, $rules, $messages);
		});
	}

}
