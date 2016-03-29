<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('app_id')->unsigned();
			$table->integer('user_id')->unsigned();
            $table->string('phone', 11);
            $table->string('content');
			$table->dateTime('pushed_at');
			$table->dateTime('poped_at');
			$table->dateTime('created_at');

			$table->index('app_id');
			$table->index('user_id');
			$table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sms_logs');
    }
}
