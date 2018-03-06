<?php

namespace App\API\v1\Validators\Concretes\Laravel ;

use App\API\v1\Validators\Concretes\Laravel\Validators\AuthSessionsValidator ;
use App\API\v1\Validators\Concretes\Laravel\Validators\UsersValidator ;
use App\API\v1\Validators\Contracts\IAuthSessionsValidator ;
use App\API\v1\Validators\Contracts\IUsersValidator ;
use Illuminate\Support\ServiceProvider ;

class LaravelValidatorsProvider extends ServiceProvider
{

	public function register ()
	{
		$map = [
			IUsersValidator::class => UsersValidator::class ,
			IAuthSessionsValidator::class => AuthSessionsValidator::class
			] ;

		foreach ( $map as $abstract => $concrete )
		{
			$this
				-> app
				-> bind ( $abstract , $concrete ) ;
		}
	}

}
