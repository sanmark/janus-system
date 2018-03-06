<?php

namespace App\API\v1\Tests\Unit\Controllers ;

use App\API\v1\Controllers\AuthSessionsController ;
use App\API\v1\Validators\Concretes\Laravel\Validators\AuthSessionsValidator ;
use App\API\v1\Validators\Exceptions\InvalidInputException ;
use App\Handlers\AuthSessionsHandler ;
use App\Models\AuthSession ;
use Illuminate\Http\JsonResponse ;
use Illuminate\Http\Request ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class AuthSessionsControllerTest extends TestCase
{

	public function testCreateOk ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$authSessionsValidator = $this -> mock ( AuthSessionsValidator::class ) ;

		$authSessionsController = new AuthSessionsController ( $authSessionsHandler , $authSessionsValidator ) ;

		$request = $this -> mock ( Request::class ) ;

		$request
			-> shouldReceive ( 'toArray' )
			-> andReturn ( [
				149 ,
			] ) ;

		$authSessionsValidator
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				[
					149 ,
				] ,
			] ) ;

		$request
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				'user_key' ,
			] )
			-> andReturn ( 'the_key' ) ;

		$request
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				'user_secret' ,
			] )
			-> andReturn ( 'the_secret' ) ;

		$authSession = $this -> mock ( AuthSession::class ) ;
		$authSession -> id = 149 ;
		$authSession -> key = 'the_key_2' ;
		$authSession -> user_id = 'the_user_id' ;

		$authSessionsHandler
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				'the_key' ,
				'the_secret' ,
			] )
			-> andReturn ( $authSession ) ;

		$response = $authSessionsController -> create ( $request ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $response ) ;
		$this -> assertEquals ( 201 , $response -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'data' => ( object ) [
					'id' => 149 ,
					'key' => 'the_key_2' ,
					'user_id' => 'the_user_id' ,
				] ,
			] , $response -> getData () ) ;
	}

	public function testCreateHandlesInvalidInputException ()
	{
		$authSessionsHandler = $this -> mock ( AuthSessionsHandler::class ) ;
		$authSessionsValidator = $this -> mock ( AuthSessionsValidator::class ) ;

		$authSessionsController = new AuthSessionsController ( $authSessionsHandler , $authSessionsValidator ) ;

		$request = $this -> mock ( Request::class ) ;

		$request
			-> shouldReceive ( 'toArray' )
			-> andReturn ( [
				149 ,
			] ) ;

		$invalidInputException = $this -> mock ( InvalidInputException::class ) ;

		$authSessionsValidator
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				[
					149 ,
				] ,
			] )
			-> andThrow ( $invalidInputException ) ;

		$invalidInputException
			-> shouldReceive ( 'getErrors' )
			-> andReturn ( 150 ) ;

		$respose = $authSessionsController -> create ( $request ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $respose ) ;
		$this -> assertEquals ( 400 , $respose -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				'errors' => 150 ,
			] , $respose -> getData () ) ;
	}

}
