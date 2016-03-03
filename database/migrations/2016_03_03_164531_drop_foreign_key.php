<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_logs', function (Blueprint $table) {
            $table->dropForeign('user_logs_app_id_foreign');
            $table->dropForeign('user_logs_user_id_foreign');

            // $table->foreign('app_id')->references('id')->on('apps')->onUpdate('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_logs', function (Blueprint $table) {
            //
        });
    }
}
