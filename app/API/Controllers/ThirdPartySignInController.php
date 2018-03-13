<?php

namespace App\API\Controllers ;

use App\API\Responses\SuccessResponse ;
use App\Handlers\GoogleAccountsHandler ;
use App\Http\Controllers\Controller ;
use App\SystemSettings\Concretes\LaravelEnv\Constants ;
use Google_Client ;
use Illuminate\Http\Request ;
use function config ;
use function response ;

class ThirdPartySignInController extends Controller
{

	private $googleAccountsHandler ;

	public function __construct (
	GoogleAccountsHandler $googleAccountsHandler
	)
	{
		$this -> googleAccountsHandler = $googleAccountsHandler ;
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
