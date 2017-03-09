<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestrunHasTestcaseTable extends Migration
{
    /**
     * Run the migrations.
     * @table TestRun_has_TestCase
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TestRun_has_TestCase', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('TestRun_id');
            $table->unsignedInteger('TestCase_id');
            $table->dateTime('LastUpdate')->nullable();
            $table->string('Status', 45)->nullable();
            $table->string('Author', 45)->nullable();
            $table->double('ExecutionDuration', 5, 2)->nullable();
            $table->string('Note')->nullable();

            $table->primary(['TestRun_id', 'TestCase_id'], 'primarykey');

            $table->foreign('TestRun_id', 'fk_TestRun_has_TestCase_TestRun1_idx')
                ->references('TestRun_id')->on('TestRun')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('TestCase_id', 'fk_TestRun_has_TestCase_TestCase1_idx')
                ->references('TestCase_id')->on('TestCase')
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
       Schema::dropIfExists('TestRun_has_TestCase');
     }
}
