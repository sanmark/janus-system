<?php

namespace App\API\Controllers ;

use App\API\Constants\Inputs\UsersInputConstants ;
use App\API\Responses\ErrorResponse ;
use App\API\Responses\SuccessResponse ;
use App\API\Validators\Constants\ResponseConstants ;
use App\API\Validators\Contracts\IUsersValidator ;
use App\API\Validators\Exceptions\InvalidInputException ;
use App\Handlers\MetasHandler ;
use App\Handlers\UserSecretResetRequestsHandler ;
use App\Handlers\UsersHandler ;
use App\Repos\Exceptions\RecordNotFoundException ;
use App\Repos\Exceptions\UniqueConstraintFailureException ;
use Illuminate\Http\Request ;
use Illuminate\Routing\Controller ;
use function response ;

class UsersController extends Controller
{

	private $metasHandler ;
	private $usersHandler ;
	private $userSecretResetRequestsHandler ;
	private $usersValidator ;

	public function __construct (
	MetasHandler $metasHandler
	, UsersHandler $usersHandler
	, UserSecretResetRequestsHandler $userSecretResetRequestsHandler
	, IUsersValidator $usersValidator
	)
	{
		$this -> metasHandler = $metasHandler ;
		$this -> usersHandler = $usersHandler ;
		$this -> userSecretResetRequestsHandler = $userSecretResetRequestsHandler ;
		$this -> usersValidator = $usersValidator ;
	}

	public function byKeyGet ( Request $request , string $key )
	{
		try
		{
			$user = $this
				-> usersHandler
				-> getByKey ( $key ) ;

			$response = new SuccessResponse ( $user -> toArrayOnly ( [
					'id' ,
					'key' ,
				] ) ) ;

			return $response -> getResponse () ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 404 ) ;

			return $response -> getResponse () ;
		}
	}

	public function create ( Request $request )
	{
		try
		{
			$data = $request -> toArray () ;

			$this
				-> usersValidator
				-> create ( $data ) ;

			$userKey = $request -> get ( UsersInputConstants::UserKey ) ;
			$userSecret = $request -> get ( UsersInputConstants::UserSecret ) ;

			$user = $this
				-> usersHandler
				-> create ( $userKey , $userSecret ) ;

			$response = new SuccessResponse ( $user -> toArrayOnly ( [
					'id' ,
					'key' ,
				] ) ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 201 ) ;
		} catch ( InvalidInputException $ex )
		{
			$response = new ErrorResponse ( $ex -> getErrors () ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 400 ) ;
		} catch ( UniqueConstraintFailureException $ex )
		{
			$response = new ErrorResponse ( [
				$ex -> getConstraint () => ResponseConstants::Duplicate ,
				] ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 409 ) ;
		}
	}

	public function update ( Request $request , $id )
	{
		try
		{
			$user = $this
				-> usersHandler
				-> update ( $id , $request -> all ( [
					UsersInputConstants::UserSecret ,
				] ) ) ;

			$response = new SuccessResponse ( $user -> toArrayOnly ( [
					'id' ,
					'key' ,
				] ) ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 200 ) ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 404 ) ;
			return $response -> getResponse () ;
		}
	}

	public function metasAll ( int $userId )
	{
		$metas = $this
			-> metasHandler
			-> getAllByUserId ( $userId ) ;

		$metasModified = [] ;

		foreach ( $metas as $meta )
		{
			$metaModified[ 'meta_key' ] = $meta -> getMetaKey () ;
			$metaModified[ 'value' ] = $meta -> value ;
			$metaModified[ 'user_id' ] = $meta -> user_id ;

			$metasModified[] = $metaModified ;
		}

		$response = new SuccessResponse ( $metasModified ) ;

		return $response -> getResponse () ;
	}

	public function metasOne ( int $userId , string $metaKey )
	{
		$meta = $this
			-> metasHandler
			-> getOneByUserIdAndMetaKey ( $userId , $metaKey ) ;

		$metaModified = [] ;
		$metaModified[ 'meta_key' ] = $meta -> getMetaKey () ;
		$metaModified[ 'value' ] = $meta -> value ;
		$metaModified[ 'user_id' ] = $meta -> user_id ;

		$response = new SuccessResponse ( $metaModified ) ;

		return $response -> getResponse () ;
	}

	public function userSecretResetRequestsCreate ( Request $request , int $userId )
	{
		$userSecretResetRequest = $this
			-> userSecretResetRequestsHandler
			-> create ( $userId ) ;

		$response = new SuccessResponse ( $userSecretResetRequest -> toArray () , 201 ) ;

		return $response -> getResponse () ;
	}

	public function userSecretResetRequestsExecute ( Request $request , int $userId )
	{
		try
		{
			$data = $request -> toArray () ;

			$this
				-> usersValidator
				-> userSecretResetRequestsExecute ( $data ) ;

			$newSecret = $request -> get ( UsersInputConstants::NewSecret ) ;
			$userSecretResetRequestToken = $request -> get ( UsersInputConstants::UserSecretResetRequestToken ) ;

			$user = $this
				-> userSecretResetRequestsHandler
				-> execute ( $userId , $userSecretResetRequestToken , $newSecret ) ;

			$response = new SuccessResponse ( $user -> toArrayOnly ( [
					'id' ,
					'key' ,
				] ) ) ;

			return $response -> getResponse () ;
		} catch ( InvalidInputException $ex )
		{
			$response = new ErrorResponse ( $ex -> getErrors () , 400 ) ;

			return $response -> getResponse () ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse (
				[
				UsersInputConstants::UserSecretResetRequestToken => ResponseConstants::NotExists ,
				]
				, 400 ) ;

			return $response -> getResponse () ;
		}
	}

}
