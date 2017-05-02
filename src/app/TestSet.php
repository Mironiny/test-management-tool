<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestSet extends Model
{
    /**
     * Define a table colum ActiveDateFrom and LastUpdate for automatic handle..
     */
    const CREATED_AT = 'ActiveDateFrom';
    const UPDATED_AT = 'LastUpdate';

    /**
     * Define a table to map a model.
     */
    protected $table = 'TestSet';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'TestSet_id';

    /**
     * Return assigned testCases.
     */
    public function testRuns()
    {
        return $this->hasMany('App\TestRun', 'TestSet_id');
    }

    /**
     * Return assigned testCases.
     */
    public function testCases()
    {
        return $this->belongsToMany('App\TestCaseHistory', 'TestCase_has_TestSet', 'TestsSet_id', 'TestCase_id');
    }

}
