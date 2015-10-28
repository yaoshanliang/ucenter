<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('roles', function(Blueprint $table)
		{
			$table->renameColumn('display_name', 'title');
		});

		Schema::table('permissions', function(Blueprint $table)
		{
			$table->renameColumn('display_name', 'title');
		});
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
