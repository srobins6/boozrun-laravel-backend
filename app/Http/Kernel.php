<?php
	namespace App\Http;

	use Illuminate\Foundation\Http\Kernel as HttpKernel;
	
	/**
	 * Class Kernel
	 *
	 * @package App\Http
	 */
	class Kernel extends HttpKernel {

		/**
		 * The application's global HTTP middleware stack.
		 * These middleware are run during every request to your application.
		 *
		 * @var array
		 */
		protected $middleware = [\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class];

		/**
		 * The application's route middleware groups.
		 *
		 * @var array
		 */
		protected $middlewareGroups = ["web"      => [Middleware\EncryptCookies::class,
		                                              \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
		                                              \Illuminate\Session\Middleware\StartSession::class,
		                                              \Illuminate\View\Middleware\ShareErrorsFromSession::class,
		                                              Middleware\VerifyCsrfToken::class,
		                                              Middleware\ForceSSL::class],
		                               "api"      => [// 			'throttle:60,1',
		                                              Middleware\EncryptCookies::class,
		                                              \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
		                                              \Illuminate\Session\Middleware\StartSession::class,
		                                              Middleware\ApiResponseFix::class,
		                                              // 			\App\Http\Middleware\CreateOrder::class
		                               ],
		                               "shopping" => [Middleware\FindStore::class,
		                                              Middleware\CreateOrder::class]];

		/**
		 * The application's route middleware.
		 * These middleware may be assigned to groups or used individually.
		 *
		 * @var array
		 */
		protected $routeMiddleware = ["auth"       => Middleware\Authenticate::class,
		                              "auth.basic" => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		                              "guest"      => Middleware\RedirectIfAuthenticated::class,
		                              "throttle"   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
		                              "control"    => Middleware\FullControl::class,
		                              "verified"   => Middleware\StripeVerify::class];
	}
