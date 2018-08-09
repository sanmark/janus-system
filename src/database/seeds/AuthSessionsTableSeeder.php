<?php

use App\Repos\Contracts\IAuthSessionsRepo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AuthSessionsTableSeeder extends Seeder
{
    private $authSessionsRepo;

    public function __construct(IAuthSessionsRepo $authSessionsRepo)
    {
        $this -> authSessionsRepo = $authSessionsRepo;
    }

    public function run()
    {
        $now = Carbon::now();

        $data = [
            [
                'key' => 'the_auth_session_key' ,
                'user_id' => 1 ,
                'created_at' => $now ,
                'updated_at' => $now ,
            ] ,
            [
                'key' => 'the_auth_session_key_expired' ,
                'user_id' => 1 ,
                'created_at' => $now -> copy() -> subDay() ,
                'updated_at' => $now -> copy() -> subDay() ,
            ] ,
            //			[
            //				'key' => '' ,
            //				'user_id' => 1 ,
            //				'created_at' => $now ,
            //				'updated_at' => $now ,
            //			] ,
        ];

        foreach ($data as $datum) {
            DB::insert(
                'INSERT INTO `auth_sessions` ' .
                '(`key`, `user_id`, `created_at`, `updated_at`) ' .
                'VALUES ("' . $datum[ 'key' ] . '", "' . $datum[ 'user_id' ] . '", "' . $datum[ 'created_at' ] . '", "' . $datum[ 'updated_at' ] . '")'
            );
        }
    }
}
