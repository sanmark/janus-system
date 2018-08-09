<?php

use App\SystemSettings\Concretes\LaravelEnv\Constants;

return [
    Constants::systemAuthSessionActiveMinutes => env(Constants::systemAuthSessionActiveMinutes, 60) ,
];
