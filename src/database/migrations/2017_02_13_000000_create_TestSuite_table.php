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
            $table->string('Name', 45);
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();
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
