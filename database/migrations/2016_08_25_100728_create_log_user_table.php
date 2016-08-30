<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('log_app', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('app_id')->unsigned();
			$table->integer('user_id');
			$table->string('request_method')->default('');
			$table->string('request_url')->default('');
			$table->text('request_params')->default('');
			$table->integer('response_code');
			$table->string('response_message')->default('');
			$table->text('response_data')->default('');
			$table->string('request_ip')->default('');
			$table->string('user_agent')->default('');
			$table->float('request_at', 14, 4);
			$table->float('pushed_at', 14, 4);
			$table->float('poped_at', 14, 4);
			$table->float('created_at', 14, 4);
			$table->timestamp('request_time');

			$table->index('app_id');
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
        //
    }
}
