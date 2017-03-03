<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsetTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestSet
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestSet', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestSet_id');
            $table->unsignedInteger('SUT_id');
            $table->unsignedInteger('TestExecutor_id');
            $table->string('Name', 45);
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();
            $table->string('Author', 45)->nullable();
            $table->string('TestSetDescription')->nullable();

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
       Schema::dropIfExists('TestSet');
     }
}
