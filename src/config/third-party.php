<?php

use App\SystemSettings\Concretes\LaravelEnv\Constants ;

return [
	Constants::thirdPartyGoogleApiClientId => env ( Constants::thirdPartyGoogleApiClientId ) ,
	Constants::thirdPartyGuzzleHttpVerify => env ( Constants::thirdPartyGuzzleHttpVerify, TRUE ) ,
	Constants::thirdPartyFacebookAppId => env ( Constants::thirdPartyFacebookAppId ) ,
	Constants::thirdPartyFacebookAppSecret => env ( Constants::thirdPartyFacebookAppSecret ) ,
	] ;
