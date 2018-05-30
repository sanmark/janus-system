<?php

namespace App\Handlers ;

use App\Models\App ;
use App\Repos\Contracts\IAppsRepo ;
use Illuminate\Contracts\Hashing\Hasher ;

class AppsHandler
{

	private $appsRepo ;
	private $hasher ;

	public function __construct (
	Hasher $hasher
	, IAppsRepo $appsRepo
	)
	{
		$this -> hasher = $hasher ;
		$this -> appsRepo = $appsRepo ;
	}

	public function isValidByKeyAndSecretHash ( string $key , string $secretHash ): bool
	{
		$app = $this -> getByKey ( $key ) ;

		$isValid = $this -> isValidBySecretHash ( $app , $secretHash ) ;

		return $isValid ;
	}

	public function isValidBySecretHash ( App $app , string $secretHash ): bool
	{
		$secret = $app -> secret ;

		$isValid = $this
			-> hasher
			-> check ( $secret , $secretHash ) ;

		return $isValid ;
	}

	public function getByKey ( string $id ): App
	{
		$app = $this
			-> appsRepo
			-> getByKey ( $id ) ;

		return $app ;
	}

}
