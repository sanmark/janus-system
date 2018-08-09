<?php

use App\Repos\Concretes\Eloquent\Models\Meta;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MetasTableSeeder extends Seeder
{
    private $carbon;

    public function __construct(
    Carbon $carbon
    ) {
        $this -> carbon = $carbon;
    }

    public function run()
    {
        $data = [
            [
                'id' => 1 ,
                'user_id' => 1 ,
                'meta_key_id' => 1 ,
                'value' => 'demo-meta-1-value' ,
                'created_at' => $this -> carbon -> now() ,
                'updated_at' => $this -> carbon -> now() ,
            ] ,
            //			[
            //				'id' => NULL ,
            //				'user_id' => NULL ,
            //				'meta_key_id' => NULL ,
            //				'value' => NULL ,
            //				'created_at' => NULL ,
            //				'updated_at' => NULL ,
            //			] ,
        ];

        foreach ($data as $datum) {
            $meta = new Meta();

            $meta -> id = $datum[ 'id' ];
            $meta -> user_id = $datum[ 'user_id' ];
            $meta -> meta_key_id = $datum[ 'meta_key_id' ];
            $meta -> value = $datum[ 'value' ];
            $meta -> created_at = $datum[ 'created_at' ];
            $meta -> updated_at = $datum[ 'updated_at' ];

            $meta -> save();
        }
    }
}
