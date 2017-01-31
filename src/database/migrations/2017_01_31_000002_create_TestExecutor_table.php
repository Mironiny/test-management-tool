<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestexecutorTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestExecutor
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestExecutor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestExecutor_id');
            $table->string('Name', 45);
            $table->tinyInteger('IsPerson')->nullable();
            $table->string('RemoteTool', 45)->nullable();
            $table->string('TestExecutorcol', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('TestExecutor');
     }
}
