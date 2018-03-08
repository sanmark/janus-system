<?php

namespace App\API\Controllers\Tests ;

use App\API\Controllers\UsersController ;
use App\API\Validators\Contracts\IUsersValidator ;
use App\API\Validators\Exceptions\InvalidInputException ;
use App\Handlers\UsersHandler ;
use App\Models\User ;
use App\Repos\Exceptions\UniqueConstraintFailureException ;
use Illuminate\Http\JsonResponse ;
use Illuminate\Http\Request ;
use Mockery ;
use Tests\TestCase ;

/**
 * @codeCoverageIgnore
 */
class UsersControllerTest extends TestCase
{

	public function testCreate_Ok ()
	{
		$mockUsersHandler = Mockery::mock ( UsersHandler::class ) ;
		$mockUsersValidator = Mockery::mock ( IUsersValidator::class ) ;
		$mockRequest = Mockery::mock ( Request::class ) ;
		$mockUserModel = Mockery::mock ( User::class ) ;

		$mockRequest
			-> shouldReceive ( 'toArray' )
			-> andReturn ( [
			] ) ;

		$mockUsersValidator
			-> shouldReceive ( 'create' ) ;

		$mockRequest
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				'user_key' ,
			] )
			-> andReturn ( 'the_key' ) ;

		$mockRequest
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				'user_secret' ,
			] )
			-> andReturn ( 'the_secret' ) ;

		$mockUsersHandler
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				'the_key' ,
				'the_secret' ,
			] )
			-> andReturn ( $mockUserModel ) ;

		$mockUserModel
			-> shouldReceive ( 'toArrayOnly' )
			-> withArgs ( [
				[
					'id' ,
					'key' ,
				] ,
			] )
			-> andReturn ( [ 149 ] ) ;

		$usersController = new UsersController ( $mockUsersHandler , $mockUsersValidator ) ;

		$r = $usersController
			-> create ( $mockRequest ) ;

		$this
			-> assertInstanceOf ( JsonResponse::class , $r ) ;

		$this
			-> assertEquals ( 201 , $r -> getStatusCode () ) ;

		$this
			-> assertEquals ( ( object ) [
					'data' => [ 149 ] ,
				] , $r -> getData () ) ;
	}

	public function testCreate_HandlesInvalidInputException ()
	{
		$mockUsersHandler = Mockery::mock ( UsersHandler::class ) ;
		$mockUsersValidator = Mockery::mock ( IUsersValidator::class ) ;
		$mockRequest = Mockery::mock ( Request::class ) ;
		$mockInvalidInputException = Mockery::mock ( InvalidInputException::class ) ;

		$mockRequest
			-> shouldReceive ( 'toArray' )
			-> andReturn ( [
			] ) ;

		$mockUsersValidator
			-> shouldReceive ( 'create' )
			-> andThrow ( $mockInvalidInputException ) ;

		$mockInvalidInputException
			-> shouldReceive ( 'getErrors' )
			-> andReturn ( 149 ) ;

		$usersController = new UsersController ( $mockUsersHandler , $mockUsersValidator ) ;

		$r = $usersController
			-> create ( $mockRequest ) ;

		$this
			-> assertInstanceOf ( JsonResponse::class , $r ) ;

		$this
			-> assertEquals ( 400 , $r -> getStatusCode () ) ;

		$this
			-> assertEquals ( ( object ) [
					'errors' => 149 ,
				] , $r -> getData () ) ;
	}

	public function testCreate_HandlesUniqueConstraintFailureException ()
	{
		$mockUsersHandler = Mockery::mock ( UsersHandler::class ) ;
		$mockUsersValidator = Mockery::mock ( IUsersValidator::class ) ;
		$mockRequest = Mockery::mock ( Request::class ) ;
		$mockUniqueConstraintFailureException = Mockery::mock ( UniqueConstraintFailureException::class ) ;

		$mockRequest
			-> shouldReceive ( 'toArray' )
			-> andReturn ( [
			] ) ;

		$mockUsersValidator
			-> shouldReceive ( 'create' ) ;

		$mockRequest
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				'user_key' ,
			] )
			-> andReturn ( 'the_key' ) ;

		$mockRequest
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				'user_secret' ,
			] )
			-> andReturn ( 'the_secret' ) ;

		$mockUsersHandler
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				'the_key' ,
				'the_secret' ,
			] )
			-> andThrow ( $mockUniqueConstraintFailureException ) ;

		$mockUniqueConstraintFailureException
			-> shouldReceive ( 'getConstraint' )
			-> andReturn ( 'rofl' ) ;

		$usersController = new UsersController ( $mockUsersHandler , $mockUsersValidator ) ;

		$r = $usersController
			-> create ( $mockRequest ) ;

		$this
			-> assertInstanceOf ( JsonResponse::class , $r ) ;

		$this
			-> assertEquals ( 409 , $r -> getStatusCode () ) ;

		$this
			-> assertObjectHasAttribute ( 'errors' , $r -> getData () ) ;
		$this
			-> assertObjectHasAttribute ( 'rofl' , $r -> getData () -> errors ) ;
		$this
			-> assertEquals ( ( object ) [
					'errors' => ( object ) [
						'rofl' => 'duplicate' ,
					] ,
				] , $r -> getData () ) ;
	}

}
