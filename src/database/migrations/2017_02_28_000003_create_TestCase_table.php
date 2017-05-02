<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestcaseTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRequirement
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestCase', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestCaseOverview_id');
            $table->unsignedInteger('TestSuite_id');
            $table->string('Name', 45);
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();

            // $table->foreign('TestSuite_id', 'fk_TestCase_TestSuite1_idx')
            //     ->references('TestSuite_id')->on('TestSuite')
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
       Schema::dropIfExists('TestCase');
     }
}
