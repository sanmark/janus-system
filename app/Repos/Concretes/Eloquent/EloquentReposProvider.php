<?php

namespace App\Repos\Concretes\Eloquent ;

use App\Repos\Concretes\Eloquent\Repos\AuthSessionsRepo ;
use App\Repos\Concretes\Eloquent\Repos\FacebookAccountsRepo ;
use App\Repos\Concretes\Eloquent\Repos\GoogleAccountsRepo ;
use App\Repos\Concretes\Eloquent\Repos\MetaKeysRepo ;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo ;
use App\Repos\Contracts\IAuthSessionsRepo ;
use App\Repos\Contracts\IFacebookAccountsRepo ;
use App\Repos\Contracts\IGoogleAccountsRepo ;
use App\Repos\Contracts\IMetaKeysRepo ;
use App\Repos\Contracts\IUsersRepo ;
use Illuminate\Support\ServiceProvider ;

class EloquentReposProvider extends ServiceProvider
{

	public function register ()
	{
		$map = [
			IAuthSessionsRepo::class => AuthSessionsRepo::class ,
			IFacebookAccountsRepo::class => FacebookAccountsRepo::class ,
			IGoogleAccountsRepo::class => GoogleAccountsRepo::class ,
			IUsersRepo::class => UsersRepo::class ,
			IMetaKeysRepo::class => MetaKeysRepo::class
			] ;

		foreach ( $map as $abstract => $concrete )
		{
			$this
				-> app
				-> bind ( $abstract , $concrete ) ;
		}
	}

}
