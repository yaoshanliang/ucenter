<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFieldsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_fields', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('title')->unique();
            $table->enum('type', ['text', 'textarea', 'select', 'checkbox', 'radio'])->default('text');
            $table->string('description')->default('');
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
        Schema::drop('user_fields');
    }

}
