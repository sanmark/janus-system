<?php

namespace App\API\Controllers ;

use App\API\Responses\SuccessResponse ;
use App\Handlers\FacebookAccountsHandler ;
use App\Handlers\GoogleAccountsHandler ;
use App\Http\Controllers\Controller ;
use App\SystemSettings\Concretes\LaravelEnv\Constants ;
use Facebook\Facebook ;
use Google_Client ;
use Illuminate\Http\Request ;
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
		$token = $request -> get ( 'token' ) ;

		$fb = new Facebook ( [
			'app_id' => config ( 'third-party.' . Constants::thirdPartyFacebookAppId ) ,
			'app_secret' => config ( 'third-party.' . Constants::thirdPartyFacebookAppSecret ) ,
			'default_graph_version' => 'v2.5' ,
			'http_client_handler' => 'stream' ,
			] ) ;

		$fb -> setDefaultAccessToken ( $token ) ;
		$response = $fb -> get ( '/me?fields=email' ) ;
		$userNode = $response -> getGraphUser () ;

		$email = $userNode -> getField ( 'email' ) ;

		if ( is_null ( $email ) )
		{
			return response ()
					-> json ()
					-> setStatusCode ( 401 ) ;
		}

		$authSession = $this
			-> facebookAccountsHandler
			-> getAuthSession ( $email ) ;

		$response = new SuccessResponse ( $authSession ) ;

		return response ()
				-> json ( $response -> getOutput () )
				-> setStatusCode ( 201 ) ;
	}

	public function google ( Request $request )
	{
		$token = $request -> get ( 'token' ) ;

		$client = new Google_Client ( [
			'client_id' => config ( Constants::thirdPartyGoogleApiClientId ) ,
			] ) ;

		$payload = $client -> verifyIdToken ( $token ) ;

		$email = $payload[ 'email' ] ;

		if ( is_null ( $email ) )
		{
			return response ()
					-> json ()
					-> setStatusCode ( 401 ) ;
		}

		$authSession = $this
			-> googleAccountsHandler
			-> getAuthSession ( $email ) ;

		$response = new SuccessResponse ( $authSession ) ;

		return response ()
				-> json ( $response -> getOutput () )
				-> setStatusCode ( 201 ) ;
	}

}
