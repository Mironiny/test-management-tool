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
            $table->dateTime('ActiveDateFrom')->nullable();
            $table->dateTime('ActiveDateTo')->nullable();
            $table->string('ProjectDescription')->nullable();
            $table->string('TestingDescription')->nullable();
            $table->string('HwRequirements', 45)->nullable();
            $table->string('SwRequirements', 45)->nullable();
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
