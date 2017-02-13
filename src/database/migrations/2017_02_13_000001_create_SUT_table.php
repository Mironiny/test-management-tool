<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSutTable extends Migration
{
    /**
     * Run the migrations.
     * @table SUT
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SUT', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('SUT_id');
            $table->string('Name', 45);
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();
            $table->string('ProjectDescription')->nullable();
            $table->string('TestingDescription')->nullable();
            $table->string('HwRequirements')->nullable();
            $table->string('SwRequirements')->nullable();
            $table->date('TestEstimatedDate')->nullable();
            $table->string('Note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('SUT');
     }
}
