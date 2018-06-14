<?php

namespace App\API\Middleware\Tests ;

use App\API\Middleware\AppVerifyMiddleware ;
use App\Handlers\AppsHandler ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Http\JsonResponse ;
use Illuminate\Http\Request ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class AppVerifyMiddlewareTest extends TestCase
{

	public function test_handle_ok ()
	{
		$appsHandler = $this -> mock ( AppsHandler::class ) ;
		$appVerifyMiddleware = new AppVerifyMiddleware ( $appsHandler ) ;
		$request = $this -> mock ( Request::class ) ;
		$next = function($requestProvidedToNext) use ($request)
		{
			if ( $requestProvidedToNext === $request )
			{
				return 149 ;
			}
		} ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-key' ,
			] )
			-> andReturn ( 'app-key' ) ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-secret-hash' ,
			] )
			-> andReturn ( 'app-secret-hash' ) ;

		$appsHandler -> shouldReceive ( 'isValidByKeyAndSecretHash' )
			-> withArgs ( [
				'app-key' ,
				'app-secret-hash' ,
			] )
			-> andReturn ( TRUE ) ;

		$response = $appVerifyMiddleware -> handle ( $request , $next ) ;

		$this -> assertSame ( 149 , $response ) ;
	}

	public function test_handle_rejectsEmptyAppKeyAndSecretHash ()
	{
		$appsHandler = $this -> mock ( AppsHandler::class ) ;
		$appVerifyMiddleware = new AppVerifyMiddleware ( $appsHandler ) ;
		$next = function()
		{
			
		} ;
		$request = $this -> mock ( Request::class ) ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-key' ,
			] )
			-> andReturnNull () ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-secret-hash' ,
			] )
			-> andReturnNull () ;

		$response = $appVerifyMiddleware -> handle ( $request , $next ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $response ) ;
		$this -> assertEquals ( 401 , $response -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'errors' => [
				] ,
			] , $response -> getData () ) ;
	}

	public function test_handle_rejectsInvalidKeyAndSecretHash ()
	{
		$appsHandler = $this -> mock ( AppsHandler::class ) ;
		$appVerifyMiddleware = new AppVerifyMiddleware ( $appsHandler ) ;
		$next = function()
		{
			
		} ;
		$request = $this -> mock ( Request::class ) ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-key' ,
			] )
			-> andReturn ( 'app-key-invalid' ) ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-secret-hash' ,
			] )
			-> andReturn ( 'app-secret-hash-invalid' ) ;

		$appsHandler -> shouldReceive ( 'isValidByKeyAndSecretHash' )
			-> withArgs ( [
				'app-key-invalid' ,
				'app-secret-hash-invalid' ,
			] )
			-> andReturn ( FALSE ) ;

		$response = $appVerifyMiddleware -> handle ( $request , $next ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $response ) ;
		$this -> assertEquals ( 401 , $response -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'errors' => [
				] ,
			] , $response -> getData () ) ;
	}

	public function test_handle_rejectsNotFoundKeyAndSecretHash ()
	{
		$appsHandler = $this -> mock ( AppsHandler::class ) ;
		$appVerifyMiddleware = new AppVerifyMiddleware ( $appsHandler ) ;
		$next = function()
		{
			
		} ;
		$request = $this -> mock ( Request::class ) ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-key' ,
			] )
			-> andReturn ( 'app-key-not-found' ) ;

		$request -> shouldReceive ( 'header' )
			-> withArgs ( [
				'x-lk-sanmark-janus-app-secret-hash' ,
			] )
			-> andReturn ( 'app-secret-hash-not-found' ) ;

		$appsHandler -> shouldReceive ( 'isValidByKeyAndSecretHash' )
			-> withArgs ( [
				'app-key-not-found' ,
				'app-secret-hash-not-found' ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$response = $appVerifyMiddleware -> handle ( $request , $next ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $response ) ;
		$this -> assertEquals ( 401 , $response -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'errors' => [
				] ,
			] , $response -> getData () ) ;
	}

}
