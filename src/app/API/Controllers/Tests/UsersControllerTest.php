<?php

namespace App\API\Controllers\Tests ;

use App\API\Constants\Inputs\UsersInputConstants ;
use App\API\Controllers\UsersController ;
use App\API\Validators\Contracts\IUsersValidator ;
use App\API\Validators\Exceptions\InvalidInputException ;
use App\Handlers\MetasHandler ;
use App\Handlers\UserSecretResetRequestsHandler ;
use App\Handlers\UsersHandler ;
use App\Models\Meta ;
use App\Models\User ;
use App\Models\UserSecretResetRequest ;
use App\Repos\Exceptions\RecordNotFoundException ;
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

	private $controller ;
	private $mockMetasHandler ;
	private $mockUsersHandler ;
	private $mockUserSecretResetRequestsHandler ;
	private $mockUsersValidator ;

	protected function setUp ()
	{
		parent::setUp () ;

		$this -> mockMetasHandler = $this -> mock ( MetasHandler::class ) ;
		$this -> mockUsersHandler = $this -> mock ( UsersHandler::class ) ;
		$this -> mockUserSecretResetRequestsHandler = $this -> mock ( UserSecretResetRequestsHandler::class ) ;
		$this -> mockUsersValidator = $this -> mock ( IUsersValidator::class ) ;

		$this -> controller = new UsersController (
			$this -> mockMetasHandler
			, $this -> mockUsersHandler
			, $this -> mockUserSecretResetRequestsHandler
			, $this -> mockUsersValidator
			) ;
	}

	public function test_byKeyGet_ok ()
	{
		$mockUser = $this
			-> mock ( User::class ) ;

		$this
			-> mockUsersHandler
			-> shouldReceive ( 'getByKey' )
			-> withArgs ( [ 'sample-key' ] )
			-> andReturn ( $mockUser ) ;

		$mockUser
			-> shouldReceive ( 'toArrayOnly' )
			-> withArgs ( [
				[
					'id' ,
					'key' ,
				] ,
			] )
			-> andReturn ( [
				'id' => 149 ,
				'key' => 'big-brother' ,
			] ) ;

		$request = $this -> mock ( Request::class ) ;
		$key = 'sample-key' ;

		$r = $this
			-> controller
			-> byKeyGet ( $request , $key ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 200 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => ( object ) [
					"id" => 149 ,
					"key" => 'big-brother' ,
				] ,
			] , $r -> getData () ) ;
	}

	public function test_byKeyGet_handlesRecordNotFoundException ()
	{
		$this
			-> mockUsersHandler
			-> shouldReceive ( 'getByKey' )
			-> withArgs ( [ 'invalid-key' ] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$request = $this -> mock ( Request::class ) ;
		$key = 'invalid-key' ;

		$r = $this
			-> controller
			-> byKeyGet ( $request , $key ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 404 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [] ,
			] , $r -> getData () ) ;
	}

	public function test_create_ok ()
	{
		$mockMetasHandler = Mockery::mock ( MetasHandler::class ) ;
		$mockUsersHandler = Mockery::mock ( UsersHandler::class ) ;
		$mockUsersValidator = Mockery::mock ( IUsersValidator::class ) ;
		$mockRequest = Mockery::mock ( Request::class ) ;
		$mockUserModel = Mockery::mock ( User::class ) ;
		$mockUserSecretsResetRequestsHandler = Mockery::mock ( UserSecretResetRequestsHandler::class ) ;

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

		$usersController = new UsersController ( $mockMetasHandler , $mockUsersHandler , $mockUserSecretsResetRequestsHandler , $mockUsersValidator ) ;

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

	public function test_create_HandlesInvalidInputException ()
	{
		$mockInvalidInputException = Mockery::mock ( InvalidInputException::class ) ;
		$mockMetasHandler = Mockery::mock ( MetasHandler::class ) ;
		$mockUsersHandler = Mockery::mock ( UsersHandler::class ) ;
		$mockUsersValidator = Mockery::mock ( IUsersValidator::class ) ;
		$mockRequest = Mockery::mock ( Request::class ) ;
		$mockUserModel = Mockery::mock ( User::class ) ;
		$mockUserSecretsResetRequestsHandler = Mockery::mock ( UserSecretResetRequestsHandler::class ) ;

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

		$usersController = new UsersController ( $mockMetasHandler , $mockUsersHandler , $mockUserSecretsResetRequestsHandler , $mockUsersValidator ) ;

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

	public function test_create_HandlesUniqueConstraintFailureException ()
	{
		$mockMetasHandler = Mockery::mock ( MetasHandler::class ) ;
		$mockUsersHandler = Mockery::mock ( UsersHandler::class ) ;
		$mockUsersValidator = Mockery::mock ( IUsersValidator::class ) ;
		$mockRequest = Mockery::mock ( Request::class ) ;
		$mockUniqueConstraintFailureException = Mockery::mock ( UniqueConstraintFailureException::class ) ;
		$mockUserSecretsResetRequestsHandler = Mockery::mock ( UserSecretResetRequestsHandler::class ) ;

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

		$usersController = new UsersController ( $mockMetasHandler , $mockUsersHandler , $mockUserSecretsResetRequestsHandler , $mockUsersValidator ) ;

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

	public function test_get_ok ()
	{
		$mockUser = $this
			-> mock ( User::class ) ;

		$this -> mockUsersHandler
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $mockUser ) ;

		$mockUser
			-> shouldReceive ( 'toArrayOnly' )
			-> withArgs ( [
				[
					'id' ,
					'key' ,
				] ,
			] )
			-> andReturn ( [
				'id' => 149 ,
				'key' => 'big-brother' ,
			] ) ;

		$userId = 149 ;

		$r = $this
			-> controller
			-> get ( $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 200 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => ( object ) [
					"id" => 149 ,
					"key" => 'big-brother' ,
				] ,
			] , $r -> getData () ) ;
	}

	public function test_get_handlesRecordNotFoundException ()
	{
		$this
			-> mockUsersHandler
			-> shouldReceive ( 'get' )
			-> withArgs ( [
				149 ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$userId = 149 ;

		$r = $this
			-> controller
			-> get ( $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 404 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [] ,
			] , $r -> getData () ) ;
	}

	public function test_metasAll_ok ()
	{
		$metas = [] ;
		foreach ( [
		[
			"meta_key" => "sample-meta-key-1" ,
			"value" => "sample-value-1" ,
			"user_id" => 1 ,
		] ,
		[
			"meta_key" => "sample-meta-key-2" ,
			"value" => "sample-value-2" ,
			"user_id" => 2 ,
		] ,
		] as $metaProto )
		{
			$meta = $this -> mock ( Meta::class ) ;

			$meta -> value = $metaProto[ 'value' ] ;
			$meta -> user_id = $metaProto[ 'user_id' ] ;

			$meta -> shouldReceive ( 'getMetaKey' )
				-> andReturn ( $metaProto[ 'meta_key' ] ) ;

			$metas[] = $meta ;
		}

		$this
			-> mockMetasHandler
			-> shouldReceive ( "getAllByUserId" )
			-> andReturn ( $metas ) ;

		$userId = 149 ;

		$r = $this -> controller
			-> metasAll ( $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 200 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => [
					( object ) [
						"meta_key" => "sample-meta-key-1" ,
						"value" => "sample-value-1" ,
						"user_id" => 1 ,
					] ,
					( object ) [
						"meta_key" => "sample-meta-key-2" ,
						"value" => "sample-value-2" ,
						"user_id" => 2 ,
					] ,
				] ,
			] , $r -> getData () ) ;
	}

	public function test_metasAll_handlesRecordNotFoundException ()
	{
		$this -> mockMetasHandler
			-> shouldReceive ( 'getAllByUserId' )
			-> withArgs ( [
				149 ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$userId = 149 ;

		$r = $this -> controller
			-> metasAll ( $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 404 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [] ,
			] , $r -> getData () ) ;
	}

	public function test_metasOne_ok ()
	{
		$meta = $this -> mock ( Meta::class ) ;

		$meta -> value = "sample-value-1" ;
		$meta -> user_id = 149 ;

		$meta -> shouldReceive ( "getMetaKey" )
			-> andReturn ( "sample-meta-key-value-1" ) ;

		$this -> mockMetasHandler
			-> shouldReceive ( "getOneByUserIdAndMetaKeyKey" )
			-> withArgs ( [
				149 ,
				"sample-meta-key-1" ,
			] )
			-> andReturn ( $meta ) ;

		$userId = 149 ;
		$metaKey = 'sample-meta-key-1' ;

		$r = $this -> controller
			-> metasOne ( $userId , $metaKey ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 200 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => ( object ) [
					"meta_key" => "sample-meta-key-value-1" ,
					"value" => "sample-value-1" ,
					"user_id" => 149 ,
				]
			] , $r -> getData () ) ;
	}

	public function test_metasOne_handlesRecordNotFoundException ()
	{
		$this -> mockMetasHandler
			-> shouldReceive ( "getOneByUserIdAndMetaKeyKey" )
			-> withArgs ( [
				149 ,
				"sample-meta-key-1" ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$userId = 149 ;
		$metaKey = 'sample-meta-key-1' ;

		$r = $this -> controller
			-> metasOne ( $userId , $metaKey ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 404 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [] ,
			] , $r -> getData () ) ;
	}

	public function test_metasUpdate_ok ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;
		$metaKey = "sample-meta-key-1" ;

		$request -> shouldReceive ( "get" )
			-> withArgs ( [ "value" ] )
			-> andReturn ( "sample-value" ) ;

		$this -> mockMetasHandler
			-> shouldReceive ( "createByUserIdAndMetaKeyKey" )
			-> withArgs ( [
				149 ,
				"sample-meta-key-1" ,
				"sample-value" ,
			] )
			-> andReturn ( 150 ) ;

		$r = $this -> controller
			-> metasUpdate ( $request , $userId , $metaKey ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 200 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => 150 ,
			] , $r -> getData () ) ;
	}

	public function test_metasUpdate_handlesInvalidInputException ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;
		$metaKey = "sample-meta-key-1" ;

		$request -> shouldReceive ( "get" )
			-> withArgs ( [ "value" ] )
			-> andReturn ( "sample-value" ) ;

		$mockInvalidInputException = $this -> mock ( InvalidInputException::class ) ;

		$mockInvalidInputException
			-> shouldReceive ( 'getErrors' )
			-> andReturn ( 150 ) ;

		$this -> mockMetasHandler
			-> shouldReceive ( "createByUserIdAndMetaKeyKey" )
			-> withArgs ( [
				149 ,
				"sample-meta-key-1" ,
				"sample-value" ,
			] )
			-> andThrow ( $mockInvalidInputException ) ;

		$r = $this -> controller
			-> metasUpdate ( $request , $userId , $metaKey ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 400 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => 150 ,
			] , $r -> getData () ) ;
	}

	public function test_metasUpdate_handlesRecordNotFoundException ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;
		$metaKey = "sample-meta-key-1" ;

		$request -> shouldReceive ( "get" )
			-> withArgs ( [ "value" ] )
			-> andReturn ( "sample-value" ) ;

		$this -> mockMetasHandler
			-> shouldReceive ( "createByUserIdAndMetaKeyKey" )
			-> withArgs ( [
				149 ,
				"sample-meta-key-1" ,
				"sample-value" ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$r = $this -> controller
			-> metasUpdate ( $request , $userId , $metaKey ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 404 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [] ,
			] , $r -> getData () ) ;
	}

	public function test_update_ok ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;

		$mockUser = $this -> mock ( User::class ) ;

		$request -> shouldReceive ( 'all' )
			-> withArgs ( [
				[
					'user_secret' ,
				] ,
			] )
			-> andReturn ( [
				'user_secret' => 'sample-user-secret' ,
			] ) ;

		$this -> mockUsersHandler
			-> shouldReceive ( 'update' )
			-> withArgs ( [
				149 ,
				[
					"user_secret" => "sample-user-secret" ,
				] ,
			] )
			-> andReturn ( $mockUser ) ;

		$mockUser
			-> shouldReceive ( 'toArrayOnly' )
			-> withArgs ( [
				[
					"id" ,
					"key" ,
				] ,
			] )
			-> andReturn ( [
				"id" => 149 ,
				"key" => "sample-key" ,
			] ) ;

		$r = $this -> controller
			-> update ( $request , $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 200 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => ( object ) [
					"id" => 149 ,
					"key" => "sample-key" ,
				] ,
			] , $r -> getData () ) ;
	}

	public function test_update_handlesRecordNotFoundException ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;

		$request -> shouldReceive ( "all" )
			-> withArgs ( [
				[
					"user_secret" ,
				] ,
			] )
			-> andReturn ( [
				"user_secret" => "sample-user-secret" ,
			] ) ;

		$this -> mockUsersHandler
			-> shouldReceive ( "update" )
			-> withArgs ( [
				149 ,
				[
					"user_secret" => "sample-user-secret" ,
				] ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$r = $this -> controller
			-> update ( $request , $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 404 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [] ,
			] , $r -> getData () ) ;
	}

	public function test_userSecretResetRequestsCreate_handlesRecordNotFoundException ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;

		$this -> mockUserSecretResetRequestsHandler
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				149 ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$r = $this -> controller
			-> userSecretResetRequestsCreate ( $request , $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 404 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [
				] ,
			] , $r -> getData () ) ;
	}

	public function test_userSecretResetRequestsCreate_ok ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;
		$userSecretResetRequest = $this -> mock ( UserSecretResetRequest::class ) ;

		$this -> mockUserSecretResetRequestsHandler
			-> shouldReceive ( 'create' )
			-> withArgs ( [
				149 ,
			] )
			-> andReturn ( $userSecretResetRequest ) ;

		$userSecretResetRequest -> shouldReceive ( 'toArray' )
			-> withArgs ( [] )
			-> andReturn ( [ 150 ] ) ;

		$r = $this -> controller
			-> userSecretResetRequestsCreate ( $request , $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 201 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => [
					150 ,
				] ,
			] , $r -> getData () ) ;
	}

	public function test_userSecretResetRequestsExecute_handlesInvalidInputException ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;

		$request -> shouldReceive ( "toArray" )
			-> withArgs ( [] )
			-> andReturn ( [
				150 ,
			] ) ;

		$invalidInputException = $this -> mock ( InvalidInputException::class ) ;

		$this -> mockUsersValidator
			-> shouldReceive ( "userSecretResetRequestsExecute" )
			-> withArgs ( [
				[
					150 ,
				] ,
			] )
			-> andThrow ( $invalidInputException ) ;

		$invalidInputException -> shouldReceive ( "getErrors" )
			-> withArgs ( [] )
			-> andReturns ( [
				151 ,
			] ) ;

		$r = $this -> controller
			-> userSecretResetRequestsExecute ( $request , $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 400 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => [
					151 ,
				] ,
			] , $r -> getData () ) ;
	}

	public function test_userSecretResetRequestsExecute_handlesRecordNotFoundException ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;

		$request -> shouldReceive ( "toArray" )
			-> andReturn ( [
				150 ,
			] ) ;

		$this -> mockUsersValidator
			-> shouldReceive ( "userSecretResetRequestsExecute" )
			-> withArgs ( [
				[
					150 ,
				] ,
			] ) ;

		$request -> shouldReceive ( "get" )
			-> withArgs ( [
				UsersInputConstants::NewSecret ,
			] )
			-> andReturn ( "sample-new-secret" ) ;

		$request -> shouldReceive ( "get" )
			-> withArgs ( [
				UsersInputConstants::UserSecretResetRequestToken ,
			] )
			-> andReturn ( "sample-token" ) ;

		$this -> mockUserSecretResetRequestsHandler
			-> shouldReceive ( "execute" )
			-> withArgs ( [
				149 ,
				"sample-token" ,
				"sample-new-secret" ,
			] )
			-> andThrow ( RecordNotFoundException::class ) ;

		$r = $this -> controller
			-> userSecretResetRequestsExecute ( $request , $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 400 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"errors" => ( object ) [
					"user_secret_reset_request_token" => [
						"not_exists" ,
					] ,
				] ,
			] , $r -> getData () ) ;
	}

	public function test_userSecretResetRequestsExecute_ok ()
	{
		$request = $this -> mock ( Request::class ) ;
		$userId = 149 ;

		$request -> shouldReceive ( "toArray" )
			-> andReturn ( [
				150 ,
			] ) ;

		$this -> mockUsersValidator
			-> shouldReceive ( "userSecretResetRequestsExecute" )
			-> withArgs ( [
				[
					150 ,
				] ,
			] ) ;

		$request -> shouldReceive ( "get" )
			-> withArgs ( [
				UsersInputConstants::NewSecret ,
			] )
			-> andReturn ( "sample-new-secret" ) ;

		$request -> shouldReceive ( "get" )
			-> withArgs ( [
				UsersInputConstants::UserSecretResetRequestToken ,
			] )
			-> andReturn ( "sample-token" ) ;

		$user = $this -> mock ( User::class ) ;

		$this -> mockUserSecretResetRequestsHandler
			-> shouldReceive ( "execute" )
			-> withArgs ( [
				149 ,
				"sample-token" ,
				"sample-new-secret" ,
			] )
			-> andReturn ( $user ) ;

		$user -> shouldReceive ( "toArrayOnly" )
			-> withArgs ( [
				[
					"id" ,
					"key" ,
				] ,
			] )
			-> andReturn ( [
				151 ,
			] ) ;

		$r = $this -> controller
			-> userSecretResetRequestsExecute ( $request , $userId ) ;

		$this -> assertInstanceOf ( JsonResponse::class , $r ) ;
		$this -> assertEquals ( 200 , $r -> getStatusCode () ) ;
		$this -> assertEquals ( ( object ) [
				"data" => [
					151 ,
				] ,
			] , $r -> getData () ) ;
	}

}
