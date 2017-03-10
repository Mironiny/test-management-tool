<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestRun extends Model
{
    /**
     * Define a table colum ActiveDateFrom and LastUpdate for automatic handle..
     */
    const CREATED_AT = 'ActiveDateFrom';
    const UPDATED_AT = 'LastUpdate';

    /**
     * Define a table to map a model.
     */
    protected $table = 'TestRun';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'TestRun_id';

    public function testSet()
    {
        return $this->belongsTo('App\TestSet', 'TestSet_id');
    }

    /**
     * Return assigned testCases.
     */
    public function testCases()
    {
        return $this->belongsToMany('App\TestCase', 'TestRun_has_TestCase', 'TestRun_id', 'TestCase_id')->withPivot('Author', 'Status', 'Note', 'ExecutionDuration', 'LastUpdate');
    }

}
