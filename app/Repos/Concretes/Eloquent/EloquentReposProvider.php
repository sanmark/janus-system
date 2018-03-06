<?php

namespace App\Repos\Concretes\Eloquent ;

use App\Repos\Concretes\Eloquent\Repos\AuthSessionsRepo ;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo ;
use App\Repos\Contracts\IAuthSessionsRepo ;
use App\Repos\Contracts\IUsersRepo ;
use Illuminate\Support\ServiceProvider ;

class EloquentReposProvider extends ServiceProvider
{

	public function register ()
	{
		$map = [
			IAuthSessionsRepo::class => AuthSessionsRepo::class ,
			IUsersRepo::class => UsersRepo::class ,
			] ;

		foreach ( $map as $abstract => $concrete )
		{
			$this
				-> app
				-> bind ( $abstract , $concrete ) ;
		}
	}

}
