<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestcaseHasTestrequirementTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestCase_has_TestRequirement
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestCase_has_TestRequirement', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestCase_TestCase_id');
            $table->unsignedInteger('TestRequirement_TestRequirement_id');


            $table->foreign('TestCase_TestCase_id', 'fk_TestCase_has_TestRequirement_TestCase1_idx')
                ->references('TestCase_id')->on('TestCase')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('TestRequirement_TestRequirement_id', 'fk_TestCase_has_TestRequirement_TestRequirement1_idx')
                ->references('TestRequirement_id')->on('TestRequirement')
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
       Schema::dropIfExists('TestCase_has_TestRequirement');
     }
}
