<?php

use Illuminate\Support\Facades\Schema ;
use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class CreateFacebookAccountsTable extends Migration
{

	public function up ()
	{
		Schema::create ( 'facebook_accounts' , function (Blueprint $t)
		{
			$t
				-> increments ( 'id' ) ;
			$t
				-> integer ( 'user_id' )
				-> unsigned () ;
			$t
				-> text ( 'key' ) ;
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
		Schema::dropIfExists ( 'facebook_accounts' ) ;
	}

}
