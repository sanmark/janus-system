<?php

namespace App\Repos\Concretes\Eloquent ;

use App\Repos\Concretes\Eloquent\Repos\AppsRepo ;
use App\Repos\Concretes\Eloquent\Repos\AuthSessionsRepo ;
use App\Repos\Concretes\Eloquent\Repos\FacebookAccountsRepo ;
use App\Repos\Concretes\Eloquent\Repos\GoogleAccountsRepo ;
use App\Repos\Concretes\Eloquent\Repos\MetaKeysRepo ;
use App\Repos\Concretes\Eloquent\Repos\MetasRepo ;
use App\Repos\Concretes\Eloquent\Repos\UserSecretResetRequestsRepo ;
use App\Repos\Concretes\Eloquent\Repos\UsersRepo ;
use App\Repos\Contracts\IAppsRepo ;
use App\Repos\Contracts\IAuthSessionsRepo ;
use App\Repos\Contracts\IFacebookAccountsRepo ;
use App\Repos\Contracts\IGoogleAccountsRepo ;
use App\Repos\Contracts\IMetaKeysRepo ;
use App\Repos\Contracts\IMetasRepo ;
use App\Repos\Contracts\IUserSecretResetRequestsRepo ;
use App\Repos\Contracts\IUsersRepo ;
use Illuminate\Support\ServiceProvider ;

class EloquentReposProvider extends ServiceProvider
{

	public function register ()
	{
		$map = [
			IAppsRepo::class => AppsRepo::class ,
			IAuthSessionsRepo::class => AuthSessionsRepo::class ,
			IFacebookAccountsRepo::class => FacebookAccountsRepo::class ,
			IGoogleAccountsRepo::class => GoogleAccountsRepo::class ,
			IMetaKeysRepo::class => MetaKeysRepo::class ,
			IMetasRepo::class => MetasRepo::class ,
			IUsersRepo::class => UsersRepo::class ,
			IUserSecretResetRequestsRepo::class => UserSecretResetRequestsRepo::class ,
			] ;

		foreach ( $map as $abstract => $concrete )
		{
			$this
				-> app
				-> bind ( $abstract , $concrete ) ;
		}
	}

}
