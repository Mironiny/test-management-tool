<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestcasehistoryTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestCase
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestCaseHistory', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestCase_id');
            $table->unsignedInteger('TestCaseOverview_id');
            $table->unsignedInteger('Version_id');
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();
            $table->tinyInteger('IsManual')->nullable();
            $table->string('TestCasePrefixes')->nullable();
            $table->string('TestSteps')->nullable();
            $table->string('ExpectedResult')->nullable();
            $table->string('TestCaseSuffixes')->nullable();
            $table->string('SourceCode')->nullable();
            $table->string('Author', 45)->nullable();
            $table->string('TestCaseDescription')->nullable();
            $table->string('Note', 255)->nullable();

            // $table->foreign('TestCaseOverview_id', 'fk_TestCase_overview_idx')
            //     ->references('TestCaseOverview_id')->on('TestCaseOverview')
            //     ->onDelete('no action')
            //     ->onUpdate('no action');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('TestCaseHistory');
     }
}
