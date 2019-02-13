<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPermanentColumnToAuthSessionsTable extends Migration
{
    public function up()
    {
        Schema::table('auth_sessions', function (Blueprint $table) {
            $table
                ->boolean('is_permanent')
                ->after('user_id')
                ->unsigned()
                ->nullable()
            ;
        });
    }

    public function down()
    {
        Schema::table('auth_sessions', function (Blueprint $table) {
            $table->dropColumn('is_permanent');
        });
    }
}
