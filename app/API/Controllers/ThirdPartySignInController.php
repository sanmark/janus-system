<?php

namespace App\API\Controllers ;

use App\API\Responses\ErrorResponse ;
use App\API\Responses\SuccessResponse ;
use App\API\Validators\Constants\ResponseConstants ;
use App\Handlers\FacebookAccountsHandler ;
use App\Handlers\GoogleAccountsHandler ;
use App\Http\Controllers\Controller ;
use App\Repos\Exceptions\RecordNotFoundException ;
use App\SystemSettings\Concretes\LaravelEnv\Constants ;
use Facebook\Facebook ;
use Illuminate\Http\Request ;
use InvalidArgumentException ;
use function config ;
use function response ;

class ThirdPartySignInController extends Controller
{

	private $facebookAccountsHandler ;
	private $googleAccountsHandler ;

	public function __construct (
	FacebookAccountsHandler $facebookAccountsHandler
	, GoogleAccountsHandler $googleAccountsHandler
	)
	{
		$this -> facebookAccountsHandler = $facebookAccountsHandler ;
		$this -> googleAccountsHandler = $googleAccountsHandler ;
	}

	public function facebook ( Request $request )
	{
		try
		{
			$token = $request -> get ( 'token' ) ;

			$fb = new Facebook ( [
				'app_id' => config ( 'third-party.' . Constants::thirdPartyFacebookAppId ) ,
				'app_secret' => config ( 'third-party.' . Constants::thirdPartyFacebookAppSecret ) ,
				'default_graph_version' => 'v2.5' ,
				'http_client_handler' => 'stream' ,
				] ) ;

			$fb -> setDefaultAccessToken ( $token ) ;
			$response = $fb -> get ( '/me?fields=first_name' ) ;
			$userNode = $response -> getGraphUser () ;

			$id = $userNode -> getId () ;
			$firstName = $userNode -> getFirstName () ;

			if ( is_null ( $id ) )
			{
				return response ()
						-> json ()
						-> setStatusCode ( 401 ) ;
			}

			$authSession = $this
				-> facebookAccountsHandler
				-> getAuthSession ( $id , $firstName ) ;

			$response = new SuccessResponse ( $authSession ) ;

			return response ()
					-> json ( $response -> getOutput () )
					-> setStatusCode ( 201 ) ;
		} catch ( InvalidArgumentException $ex )
		{
			$response = new ErrorResponse ( [
				'token' => [
					ResponseConstants::Required ,
				] ,
				] , 400 ) ;

			return $response -> getResponse () ;
		}
	}

	public function google ( Request $request )
	{
		$token = $request -> get ( 'token' ) ;

		try
		{
			$authSession = $this
				-> googleAccountsHandler
				-> getAuthSessionByToken ( $token ) ;

			$response = new SuccessResponse ( $authSession ) ;

			return $response -> getResponse () ;
		} catch ( RecordNotFoundException $ex )
		{
			$response = new ErrorResponse ( [] , 404 ) ;

			return $response -> getResponse () ;
		}
	}

}
