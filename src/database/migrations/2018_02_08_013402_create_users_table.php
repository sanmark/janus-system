<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $t) {
            $t
                -> increments('id');
            $t
                -> string('key')
                -> nullable(false)
                -> unique('key', 'key');
            $t
                -> string('secret')
                -> nullable(false);

            $t
                -> softDeletes();
            $t
                -> timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
