<?php

use Illuminate\Support\Facades\Schema ;
use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class CreateAppsTable extends Migration
{

	public function up ()
	{
		Schema::create ( 'apps' , function (Blueprint $t)
		{
			$t -> increments ( 'id' ) ;
			$t -> string ( 'key' )
				-> unique () ;
			$t -> string ( 'secret' ) ;
			$t -> timestamps () ;
		} ) ;
	}

	public function down ()
	{
		Schema::dropIfExists ( 'apps' ) ;
	}

}
