<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestrunTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRun
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestRun', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestRun_id');
            $table->unsignedInteger('SUT_id');
            $table->unsignedInteger('TestExecutor_id');
            $table->string('Name', 45);
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->string('Author', 45)->nullable();
            $table->string('TestRunDescription')->nullable();


            $table->foreign('SUT_id', 'fk_Test Run_SUT1_idx')
                ->references('SUT_id')->on('SUT')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('TestExecutor_id', 'fk_Test Run_Test Executor1_idx')
                ->references('TestExecutor_id')->on('TestExecutor')
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
       Schema::dropIfExists('TestRun');
     }
}
