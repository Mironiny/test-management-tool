<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasSUT extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('User_has_SUT', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('SUT_id');
            $table->unsignedInteger('id');
            $table->string('Role', 45)->nullable();

            $table->primary(['SUT_id', 'id'], 'primarykey');

            $table->foreign('SUT_id', 'fk_User_has_SUT_TestRun1_idx')
                ->references('SUT_id')->on('SUT')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('id', 'fk_User_has_SUT_TestRun2_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');
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
