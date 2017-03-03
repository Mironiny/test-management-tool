<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestcaseHasTestsetTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestCase_has_TestSet
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestCase_has_TestSet', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('TestCase_id');
            $table->unsignedInteger('TestsSet_id');
            $table->tinyInteger('Pass')->nullable();

            $table->primary(['TestCase_id', 'TestsSet_id'], 'primarykey');

            $table->foreign('TestCase_id', 'fk_Test Case_has_Test Run_Test Case1_idx')
                ->references('TestCase_id')->on('TestCase')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('TestsSet_id', 'fk_Test Case_has_Test Run_Test Run1_idx')
                ->references('TestSet_id')->on('TestSet')
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
       Schema::dropIfExists('TestCase_has_TestSet');
     }
}
