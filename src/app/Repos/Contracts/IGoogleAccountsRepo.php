<?php

namespace App\Repos\Contracts ;

use App\Models\GoogleAccount ;

interface IGoogleAccountsRepo
{

	public function create ( int $userId , string $key ): GoogleAccount ;

	public function get ( int $id ): GoogleAccount ;

	public function getByKey ( string $key ): GoogleAccount ;
}
