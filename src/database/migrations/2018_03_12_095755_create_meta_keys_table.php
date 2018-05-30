<?php

use Illuminate\Support\Facades\Schema ;
use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class CreateMetaKeysTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create ( 'meta_keys' , function (Blueprint $table)
		{
			$table -> increments ( 'id' ) ;
			$table -> string ( 'key' ) ;
			$table -> timestamps () ;
		} ) ;
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down ()
	{
		Schema::dropIfExists ( 'meta_keys' ) ;
	}

}
