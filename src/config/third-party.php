<?php

use App\SystemSettings\Concretes\LaravelEnv\Constants;

return [
    Constants::thirdPartyGoogleApiClientId => env(Constants::thirdPartyGoogleApiClientId) ,
    Constants::thirdPartyGuzzleHttpVerify => env(Constants::thirdPartyGuzzleHttpVerify, true) ,
    Constants::thirdPartyFacebookAppId => env(Constants::thirdPartyFacebookAppId) ,
    Constants::thirdPartyFacebookAppSecret => env(Constants::thirdPartyFacebookAppSecret) ,
];
