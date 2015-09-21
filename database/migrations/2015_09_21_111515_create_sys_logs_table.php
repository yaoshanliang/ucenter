<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_logs', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('app_id')->unsigned();
			$table->string('level');
			$table->string('message');
			$table->text('context')->default('');
			$table->string('ip')->default('');
			$table->dateTime('created_at');

            $table->foreign('app_id')->references('id')->on('apps')
                ->onUpdate('cascade')->onDelete('cascade');

			$table->index('app_id');
			$table->index('level');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sys_logs');
	}

}
