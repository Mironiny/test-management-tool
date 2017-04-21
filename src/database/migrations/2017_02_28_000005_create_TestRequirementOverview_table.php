<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestrequirementoverviewTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRequirement
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestRequirementOverview', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestRequirementOverview_id');
            $table->unsignedInteger('SUT_id');
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();

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
