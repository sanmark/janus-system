<?php

use Illuminate\Support\Facades\Schema ;
use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class CreateUsersTable extends Migration
{

	public function up ()
	{
		Schema::create ( 'users' , function (Blueprint $t)
		{
			$t
				-> increments ( 'id' ) ;
			$t
				-> string ( 'key' )
				-> nullable ( FALSE )
				-> unique ( 'key' , 'key' ) ;
			$t
				-> string ( 'secret' )
				-> nullable ( FALSE ) ;

			$t
				-> softDeletes () ;
			$t
				-> timestamps () ;
		} ) ;
	}

	public function down ()
	{
		Schema::dropIfExists ( 'users' ) ;
	}

}
