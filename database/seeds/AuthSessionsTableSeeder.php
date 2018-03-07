<?php

use App\Repos\Contracts\IAuthSessionsRepo ;
use Carbon\Carbon ;
use Illuminate\Database\Seeder ;

class AuthSessionsTableSeeder extends Seeder
{

	private $authSessionsRepo ;

	public function __construct ( IAuthSessionsRepo $authSessionsRepo )
	{
		$this -> authSessionsRepo = $authSessionsRepo ;
	}

	public function run ()
	{
		$data = [
			[
				'key' => 'the_auth_session_key' ,
				'user_id' => 1 ,
			] ,
//			[
//				'key' => '' ,
//				'user_id' => ,
//			] ,
			] ;

		foreach ( $data as $datum )
		{
			$now = Carbon::now () ;
			DB::insert (
				'INSERT INTO `auth_sessions` ' .
				'(`key`, `user_id`, `created_at`, `updated_at`) ' .
				'VALUES ("' . $datum[ 'key' ] . '", "' . $datum[ 'user_id' ] . '", "' . $now . '", "' . $now . '")'
			) ;
		}
	}

}
