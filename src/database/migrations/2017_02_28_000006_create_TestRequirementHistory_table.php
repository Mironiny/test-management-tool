<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestrequirementHistoryTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRequirement
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestRequirementHistory', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('TestRequirementOverview_id');
            $table->increments('TestRequirement_id');
            $table->string('Name', 45);
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();
            $table->string('CoverageCriteria', 45)->nullable();
            $table->string('RequirementDescription')->nullable();

            $table->foreign('TestRequirementOverview_id', 'fk_Test Requerements_OVERVIEW_idx')
                ->references('TestRequirementOverview_id')->on('TestRequirement')
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
       Schema::dropIfExists('TestRequirementHistory');
     }
}
