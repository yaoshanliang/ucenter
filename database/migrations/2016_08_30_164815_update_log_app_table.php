<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientToLogAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_app', function (Blueprint $table) {
			$table->string('user_client')->default('')->after('response_data');
			$table->string('user_ip')->default('')->after('response_data');
			$table->string('server_ip')->default('')->after('user_agent');
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
