<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestrequirementTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRequirement
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestRequirement', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestRequirement_id');
            $table->unsignedInteger('SUT_id');
            $table->string('Name', 45);
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();
            $table->string('CoverageCriteria', 45)->nullable();
            $table->string('RequirementDescription')->nullable();


            $table->foreign('SUT_id', 'fk_Test Requerements_SUT_idx')
                ->references('SUT_id')->on('SUT')
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
       Schema::dropIfExists('TestRequirement');
     }
}
