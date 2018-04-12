<?php

use App\Repos\Concretes\Eloquent\Models\App ;
use Carbon\Carbon ;
use Illuminate\Database\Seeder ;

class AppsTableSeeder extends Seeder
{

	private $carbon ;

	public function __construct (
	Carbon $carbon
	)
	{
		$this -> carbon = $carbon ;
	}

	public function run ()
	{
		$data = [
			[
				'id' => 1 ,
				'key' => 'key' ,
				'secret' => 'secret' ,
				'created_at' => $this -> carbon -> now () ,
				'updated_at' => $this -> carbon -> now () ,
			] ,
//			[
//				'id' => NULL ,
//				'key' => NULL ,
//				'secret' => NULL ,
//				'created_at' => $this -> carbon -> now () ,
//				'updated_at' => $this -> carbon -> now () ,
//			] ,
			] ;

		foreach ( $data as $datum )
		{
			$app = new App() ;

			$app -> id = $datum[ 'id' ] ;
			$app -> key = $datum[ 'key' ] ;
			$app -> secret = $datum[ 'secret' ] ;
			$app -> created_at = $datum[ 'created_at' ] ;
			$app -> updated_at = $datum[ 'updated_at' ] ;

			$app -> save () ;
		}
	}

}
