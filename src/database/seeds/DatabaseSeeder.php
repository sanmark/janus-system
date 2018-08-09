<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this -> call(UsersTableSeeder::class);
        $this -> call(AuthSessionsTableSeeder::class);
        $this -> call(MetaKeysSeeder::class);
        $this -> call(AppsTableSeeder::class);
        $this -> call(MetasTableSeeder::class);
    }
}
