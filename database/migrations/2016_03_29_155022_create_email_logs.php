<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('app_id')->unsigned();
			$table->integer('user_id')->unsigned();
            $table->string('email');
            $table->string('content');
			$table->dateTime('pushed_at');
			$table->dateTime('poped_at');
			$table->dateTime('created_at');

			$table->index('app_id');
			$table->index('user_id');
			$table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_logs');
    }
}
