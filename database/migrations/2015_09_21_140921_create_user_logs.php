<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_logs', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('app_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->enum('type', ['A', 'D', 'U', 'S'])->default('S');
			$table->string('object')->default('');
			$table->string('data')->default('');
			$table->string('sql')->default('');
			$table->string('ip')->default('');
			$table->dateTime('created_at');

            $table->foreign('app_id')->references('id')->on('apps')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

			$table->index('app_id');
			$table->index('user_id');
			$table->index('type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_logs');
	}

}
