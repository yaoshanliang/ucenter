<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_access', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('app_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->enum('type', ['access', 'exit'])->default('access');
			$table->string('title')->default('');
			$table->string('description')->default('');
			$table->integer('handler_id')->unsigned();
			$table->enum('result', ['agree', 'disagree'])->default('agree');
			$table->string('reason')->default('');
			$table->dateTime('handled_at');

            $table->timestamps();

			$table->foreign('app_id')->references('id')->on('apps')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('handler_id')->references('id')->on('users')
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
        Schema::table('app_access', function (Blueprint $table) {
            //
        });
    }
}
