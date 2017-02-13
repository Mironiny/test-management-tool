<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsuiteHasTestrunTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestSuite_has_TestRun
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestSuite_has_TestRun', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('TestSuite_TestSuite_id');
            $table->unsignedInteger('TestRun_TestRun_id');


            $table->foreign('TestSuite_TestSuite_id', 'fk_TestSuite_has_TestRun_TestSuite1_idx')
                ->references('TestSuite_id')->on('TestSuite')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('TestRun_TestRun_id', 'fk_TestSuite_has_TestRun_TestRun1_idx')
                ->references('TestRun_id')->on('TestRun')
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
       Schema::dropIfExists('TestSuite_has_TestRun');
     }
}
