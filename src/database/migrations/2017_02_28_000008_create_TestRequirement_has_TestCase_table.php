<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestrequirementHasTestcaseTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRequirement_has_TestCase
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestRequirement_has_TestCase', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('TestRequirement_id');
            $table->unsignedInteger('TestCase_id');

            //$table->primary(['TestRequirement_id', 'TestCase_id']);

            $table->primary(['TestRequirement_id', 'TestCase_id'], 'primarykey');

            $table->foreign('TestRequirement_id', 'fk_TestRequirement_has_TestCase_TestRequirement1_idx')
                ->references('TestRequirement_id')->on('TestRequirement')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('TestCase_id', 'fk_TestRequirement_has_TestCase_TestCase1_idx')
                ->references('TestCase_id')->on('TestCase')
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
       Schema::dropIfExists('TestRequirement_has_TestCase');
     }
}
