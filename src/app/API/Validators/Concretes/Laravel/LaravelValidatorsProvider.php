<?php

namespace App\API\Validators\Concretes\Laravel ;

use App\API\Validators\Concretes\Laravel\Validators\AuthSessionsValidator ;
use App\API\Validators\Concretes\Laravel\Validators\MetasValidator ;
use App\API\Validators\Concretes\Laravel\Validators\UsersValidator ;
use App\API\Validators\Contracts\IAuthSessionsValidator ;
use App\API\Validators\Contracts\IMetasValidator ;
use App\API\Validators\Contracts\IUsersValidator ;
use Illuminate\Support\ServiceProvider ;

class LaravelValidatorsProvider extends ServiceProvider
{

	public function register ()
	{
		$map = [
			IUsersValidator::class => UsersValidator::class ,
			IAuthSessionsValidator::class => AuthSessionsValidator::class ,
			IMetasValidator::class => MetasValidator::class
			] ;

		foreach ( $map as $abstract => $concrete )
		{
			$this
				-> app
				-> bind ( $abstract , $concrete ) ;
		}
	}

}
