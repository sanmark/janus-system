<?php

namespace App\API\v1\Controllers ;

use App\API\v1\Constants\Headers\RequestHeaderConstants ;
use App\API\v1\Constants\UserInputs\UsersInputConstants ;
use App\API\v1\Responses\ErrorResponse ;
use App\API\v1\Responses\SuccessResponse ;
use App\API\v1\Validators\Contracts\IAuthSessionsValidator ;
use App\API\v1\Validators\Exceptions\InvalidInputException ;
use App\Handlers\AuthSessionsHandler ;
use App\Repos\Exceptions\RecordNotFoundException ;
use Illuminate\Http\Request ;
use function dd ;
use function response ;

class AuthSessionsController
{

	private $authSessionsHandler ;
	private $authSessionsValidator ;

	public function __construct (
	AuthSessionsHandler $authSessionsHandler
	, IAuthSessionsValidator $authSessionsValidator
	)
	{
		$this -> authSessionsHandler = $authSessionsHandler ;
		$this -> authSessionsValidator = $authSessionsValidator ;
	}

	public function create ( Request $request )
	{
		try
		{
			$data = $request -> toArray () ;

			$this
				-> authSessionsValidator
				-> create ( $data ) ;

			$userKey = $request -> get ( UsersInputConstants::UserKey ) ;
			$userSecret = $request -> get ( UsersInputConstants::UserSecret ) ;

			$authSession = $this
				-> authSessionsHandler
				-> create ( $userKey , $userSecret ) ;

			$response = new SuccessResponse ( $authSession ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 201 ) ;
		} catch ( InvalidInputException $ex )
		{
			$response = new ErrorResponse ( $ex -> getErrors () ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 400 ) ;
		} catch ( RecordNotFoundException $ex )
		{
			return response ()
					-> json ()
					-> setStatusCode ( 401 ) ;
		}
	}

	public function validate ( Request $request )
	{
		try
		{
			$key = $request -> header ( RequestHeaderConstants::SESSION_KEY ) ;

			if ( is_null ( $key ) )
			{
				return response ()
						-> json ()
						-> setStatusCode ( 401 ) ;
			}

			$authSession = $this
				-> authSessionsHandler
				-> getByKey ( $key ) ;

			$response = new SuccessResponse ( $authSession ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 200 ) ;
		} catch ( RecordNotFoundException $ex )
		{
			return response ()
					-> json ()
					-> setStatusCode ( 401 ) ;
		}
	}

}
