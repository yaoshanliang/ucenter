<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLogTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('user_logs', 'log_user');
        Schema::rename('app_logs', 'log_app');
        Schema::rename('sms_logs', 'log_sms');
        Schema::rename('email_logs', 'log_email');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
