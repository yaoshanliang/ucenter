<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeResultDefaultToEmpty4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_access', function (Blueprint $table) {
            $table->enum('result', ['', 'agree', 'disagree'])->default('')->after('handler_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_access', function (Blueprint $table) {
            //
        });
    }
}
