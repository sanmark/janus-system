<?php

namespace App\Providers ;

use App\API\Middleware\AppVerifyMiddleware ;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider ;
use Illuminate\Support\Facades\Route ;
use function base_path ;

class RouteServiceProvider extends ServiceProvider
{

	/**
	 * This namespace is applied to your controller routes.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers' ;

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @return void
	 */
	public function boot ()
	{
		//

		parent::boot () ;
	}

	/**
	 * Define the routes for the application.
	 *
	 * @return void
	 */
	public function map ()
	{
		$this -> mapApiRoutes () ;
		$this -> mapWebRoutes () ;
	}

	protected function mapApiRoutes ()
	{
		Route::group ( [
			'prefix' => 'api/' ,
			'middleware' => AppVerifyMiddleware::class ,
			] , function()
		{
			$path = base_path ( 'app/API/routes/*.php' ) ;
			$files = glob ( $path ) ;

			foreach ( $files as $file )
			{
				require $file ;
			}
		} ) ;
	}

	protected function mapWebRoutes ()
	{
		Route::group ( [
			] , function()
		{
			$path = base_path ( 'app/Web/routes/*.php' ) ;
			$files = glob ( $path ) ;

			foreach ( $files as $file )
			{
				require $file ;
			}
		} ) ;
	}

}
