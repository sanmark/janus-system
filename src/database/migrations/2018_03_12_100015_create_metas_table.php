<?php

use Illuminate\Support\Facades\Schema ;
use Illuminate\Database\Schema\Blueprint ;
use Illuminate\Database\Migrations\Migration ;

class CreateMetasTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up ()
	{
		Schema::create ( 'metas' , function (Blueprint $table)
		{
			$table -> increments ( 'id' ) ;
			$table -> integer ( 'user_id' ) -> unsigned () ;
			$table -> integer ( 'meta_key_id' ) -> unsigned () ;
			$table -> string ( 'value' ) ;
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
		Schema::dropIfExists ( 'metas' ) ;
	}

}
