<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_role', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
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
