<?php

use App\Repos\Concretes\Eloquent\Models\MetaKey;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MetaKeysSeeder extends Seeder
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
                'key' => 'demo-meta-1' ,
                'created_at' => $this -> carbon -> now() ,
                'updated_at' => $this -> carbon -> now() ,
            ] ,
            [
                'id' => 2 ,
                'key' => 'demo-meta-2' ,
                'created_at' => $this -> carbon -> now() ,
                'updated_at' => $this -> carbon -> now() ,
            ] ,
            //			[
            //				'id' => NULL ,
            //				'key' => NULL ,
            //				'created_at' => NULL ,
            //				'updated_at' => NULL ,
            //			] ,
        ];

        foreach ($data as $datum) {
            $metaKey = new MetaKey();

            $metaKey -> id = $datum[ 'id' ];
            $metaKey -> key = $datum[ 'key' ];
            $metaKey -> created_at = $datum[ 'created_at' ];
            $metaKey -> updated_at = $datum[ 'updated_at' ];

            $metaKey -> save();
        }
    }
}
