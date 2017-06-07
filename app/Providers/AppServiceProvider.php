<?php
	namespace App\Providers;
	
	use Hash;
	use Illuminate\Support\ServiceProvider;
	use Validator;
	
	/**
	 * Class AppServiceProvider
	 *
	 * @package App\Providers
	 */
	class AppServiceProvider extends ServiceProvider {
		
		/**
		 * Bootstrap any application services.
		 *
		 * @return void
		 */
		public function boot() {
			view()->share("numCols", 10);
			
			/** @noinspection PhpUnusedParameterInspection */
			Validator::extend("password_match",
				function ($attribute, $value, $parameters, $validator) {
					return Hash::check($value, auth($parameters[0])->user()->password);
				});
			Validator::replacer("password_match",
				function () {
					return "Correct current password required.";
				});
		}
		
		/**
		 * Register any application services.
		 *
		 * @return void
		 */
		public function register() {
			$this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
		}
	}
