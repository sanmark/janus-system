<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppsTable extends Migration
{
    public function up()
    {
        Schema::create('apps', function (Blueprint $t) {
            $t -> increments('id');
            $t -> string('key')
                -> unique();
            $t -> string('secret');
            $t -> timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apps');
    }
}
