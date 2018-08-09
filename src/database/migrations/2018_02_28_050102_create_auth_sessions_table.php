<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('auth_sessions', function (Blueprint $t) {
            $t
                -> increments('id');
            $t
                -> string('key');
            $t
                -> integer('user_id') -> unsigned();
            $t
                -> timestamps();

            $t
                -> foreign('user_id')
                -> references('id')
                -> on('users')
                -> onDelete('cascade')
                -> onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('auth_sessions', function (Blueprint $t) {
            $t -> dropForeign([
                'user_id' ,
            ]);
        });

        Schema::dropIfExists('auth_sessions');
    }
}
