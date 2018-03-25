<?php

use Illuminate\Support\Facades\Schema ;
use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class CreateUserSecretResetRequestsTable extends Migration
{

	public function up ()
	{
		Schema::create ( 'user_secret_reset_requests' , function (Blueprint $t)
		{
			$t
				-> increments ( 'id' ) ;
			$t
				-> integer ( 'user_id' )
				-> unsigned () ;
			$t
				-> string ( 'token' ) ;
			$t
				-> timestamps () ;

			$t
				-> foreign ( 'user_id' )
				-> references ( 'id' )
				-> on ( 'users' )
				-> onDelete ( 'cascade' )
				-> onUpdate ( 'cascade' ) ;
		} ) ;
	}

	public function down ()
	{
		Schema::dropIfExists ( 'user_secret_reset_requests' ) ;
	}

}
