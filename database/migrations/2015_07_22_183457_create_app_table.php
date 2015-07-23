<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('app', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('app')->unique();
			$table->string('app_name');
			$table->string('app_secret');
			$table->string('app_home_url');
			$table->string('app_login_url');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
