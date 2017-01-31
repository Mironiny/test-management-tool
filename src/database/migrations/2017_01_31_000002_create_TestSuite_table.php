<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsuiteTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestSuite
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestSuite', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestSuite_id');
            $table->dateTime('ActiveDateFrom')->nullable();
            $table->dateTime('ActiveDateTo')->nullable();
            $table->string('TestSuiteGoals')->nullable();
            $table->string('TestSuiteVersion', 45)->nullable();
            $table->string('TestSuiteDocumentation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('TestSuite');
     }
}
