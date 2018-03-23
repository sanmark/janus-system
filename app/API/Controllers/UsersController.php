<?php

namespace App\API\Controllers ;

use App\API\Constants\UserInputs\UsersInputConstants ;
use App\API\Responses\ErrorResponse ;
use App\API\Responses\SuccessResponse ;
use App\API\Validators\Constants\ResponseConstants ;
use App\API\Validators\Contracts\IUsersValidator ;
use App\API\Validators\Exceptions\InvalidInputException ;
use App\Handlers\UsersHandler ;
use App\Repos\Exceptions\RecordNotFoundException ;
use App\Repos\Exceptions\UniqueConstraintFailureException ;
use Illuminate\Http\Request ;
use Illuminate\Routing\Controller ;
use function response ;

class UsersController extends Controller
{

	private $usersHandler ;
	private $usersValidator ;

	public function __construct (
	UsersHandler $usersHandler
	, IUsersValidator $usersValidator
	)
	{
		$this -> usersHandler = $usersHandler ;
		$this -> usersValidator = $usersValidator ;
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

}
