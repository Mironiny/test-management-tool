<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestrunTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRun
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestRun', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestRun_id');
            $table->unsignedInteger('TestSet_id');
            $table->string('Name', 45)->nullable();
            $table->dateTime('ActiveDateFrom');
            $table->dateTime('ActiveDateTo')->nullable();
            $table->dateTime('LastUpdate')->nullable();


            $table->foreign('TestSet_id', 'fk_TestRun_TestSet1_idx')
                ->references('TestSet_id')->on('TestSet')
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
       Schema::dropIfExists('TestRun');
     }
}
