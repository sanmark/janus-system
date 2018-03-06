<?php

use App\API\v1\Constants\UserInputs\UsersInputConstants ;
use App\Repos\Contracts\IUsersRepo ;
use Illuminate\Database\Seeder ;

class UsersTableSeeder extends Seeder
{

	private $usersRepo ;

	public function __construct ( IUsersRepo $usersRepo )
	{
		$this -> usersRepo = $usersRepo ;
	}

	public function run ()
	{
		$data = [
			[
				UsersInputConstants::UserKey => 'user1' ,
				UsersInputConstants::UserSecret => 'sec1' ,
			] ,
//			[
//				UsersInputConstants::UserKey => '' ,
//				UsersInputConstants::UserSecret => '' ,
//			] ,
			] ;

		foreach ( $data as $datum )
		{
			$this
				-> usersRepo
				-> create ( $datum[ UsersInputConstants::UserKey ] , $datum[ UsersInputConstants::UserSecret ] ) ;
		}
	}

}
