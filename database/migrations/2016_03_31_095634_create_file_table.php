<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('app_id')->unsigned();
			$table->integer('user_id')->unsigned();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('extension');
            $table->string('mime_type');
            $table->integer('size');
			$table->dateTime('created_at');

            $table->foreign('app_id')->references('id')->on('apps')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

			$table->index('app_id');
			$table->index('user_id');
			$table->index('file_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('files');
    }
}
