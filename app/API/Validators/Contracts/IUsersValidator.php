<?php

namespace App\API\Validators\Contracts ;

interface IUsersValidator
{

	public function create ( array $data ) ;

	public function userSecretResetRequestsExecute ( array $data ) ;
}
