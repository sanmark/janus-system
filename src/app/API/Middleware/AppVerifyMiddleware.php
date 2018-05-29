<?php

namespace App\API\Middleware ;

use App\API\Constants\Headers\RequestHeaderConstants ;
use App\API\Responses\ErrorResponse ;
use App\Handlers\AppsHandler ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Closure ;
use Illuminate\Http\Request ;

class AppVerifyMiddleware
{

	private $appsHandler ;

	public function __construct ( AppsHandler $appsHandler )
	{
		$this -> appsHandler = $appsHandler ;
	}

	public function handle ( Request $request , Closure $next , $guard = null )
	{
		$key = $request -> header ( RequestHeaderConstants::APP_KEY ) ;
		$secretHash = $request -> header ( RequestHeaderConstants::APP_SECRET_HASH ) ;

		if(
			is_null($key) ||
			is_null($secretHash)
		){
			$response = new ErrorResponse ( [] , 401 ) ;

			return $response -> getResponse () ;			
		}

		try
		{
			$isValid = $this
				-> appsHandler
				-> isValidByKeyAndSecretHash ( $key , $secretHash ) ;

			if ( ! $isValid )
			{
				$response = new ErrorResponse ( [] , 401 ) ;

				return $response -> getResponse () ;
			}

			return $next ( $request ) ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 401 ) ;

			return $response -> getResponse () ;
		}
	}

}
