<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestcaseHasTestrunTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestCase_has_TestRun
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestCase_has_TestRun', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestCase_id');
            $table->unsignedInteger('TestRun_id');
            $table->tinyInteger('Pass')->nullable();


            $table->foreign('TestCase_id', 'fk_Test Case_has_Test Run_Test Case1_idx')
                ->references('TestCase_id')->on('TestCase')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('TestRun_id', 'fk_Test Case_has_Test Run_Test Run1_idx')
                ->references('TestRun_id')->on('TestRun')
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
       Schema::dropIfExists('TestCase_has_TestRun');
     }
}
