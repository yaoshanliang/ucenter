<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('app_id')->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->default('');
            $table->timestamps();
			$table->softDeletes();

            $table->foreign('app_id')->references('id')->on('apps')
                ->onUpdate('cascade')->onDelete('cascade');

			$table->index('app_id');
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('user_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
			$table->integer('app_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->timestamps();
			$table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('app_id')->references('id')->on('apps')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('app_id')->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->default('');
            $table->timestamps();
			$table->softDeletes();

            $table->foreign('app_id')->references('id')->on('apps')
                ->onUpdate('cascade')->onDelete('cascade');

			$table->index('app_id');
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('role_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned;
            $table->integer('permission_id')->unsigned;
            $table->timestamps();
			$table->softDeletes();

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::drop('role_permission');
        Schema::drop('permissions');
        Schema::drop('user_role');
        Schema::drop('roles');
    }
}
