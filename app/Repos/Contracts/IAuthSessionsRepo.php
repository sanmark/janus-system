<?php

namespace App\Repos\Contracts ;

use App\Models\AuthSession ;

interface IAuthSessionsRepo
{

	public function create ( int $userId ): AuthSession ;
}
