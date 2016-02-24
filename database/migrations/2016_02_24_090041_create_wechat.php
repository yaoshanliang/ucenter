<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wechat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('unionid')->default('');
            $table->string('openid')->default('');
            $table->string('nickname')->default('');
            $table->integer('sex')->default(0);
            $table->string('language')->default('');
            $table->string('city')->default('');
            $table->string('province')->default('');
            $table->string('country')->default('');
            $table->string('headimgurl')->default('');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wechat', function (Blueprint $table) {
            //
        });
    }
}
