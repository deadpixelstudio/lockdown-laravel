<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('has_users')->default(0);
            $table->string('name');
            $table->string('slug');
            $table->nestedSet();
        });

        Schema::create('group_permission', function (Blueprint $table) {
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('permission_id')->unsigned();

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['group_id', 'permission_id']);
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->bigInteger('group_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->primary(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('group_permission');
        Schema::dropIfExists('groups');
    }
}
