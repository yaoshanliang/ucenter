<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAppsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('apps', function(Blueprint $table)
		{
			$table->renameColumn('app', 'name');
			$table->renameColumn('app_name', 'title');
			$table->renameColumn('app_secret', 'secret');
			$table->renameColumn('app_home_url', 'home_url');
			$table->renameColumn('app_login_url', 'login_url');
            $table->string('description')->default('')->after('app_login_url');
            $table->integer('user_id')->unsigned()->after('description');

			$table->foreign('user_id')->references('id')->on('users')
				->onUpdate('cascade')->onDelete('cascade');

			$table->index('user_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('apps', function(Blueprint $table)
		{
			//
		});
	}

}
