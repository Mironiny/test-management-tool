<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    /**
     * Define a table colum ActiveDateFrom and LastUpdate for automatic handle..
     */
    const CREATED_AT = 'ActiveDateFrom';
    const UPDATED_AT = 'LastUpdate';

    /**
     * Define a table to map a model.
     */
    protected $table = 'TestCase';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'TestCaseOverview_id';

    /**
     * Return assigned testCases.
     */
    public function testCases()
    {
        return $this->hasMany('App\TestCaseHistory', 'TestCaseOverview_id');
    }

    /**
    * Get the post that owns the comment.
    */
    public function testSuite()
    {
        return $this->belongsTo('App\TestSuite', 'TestSuite_id');
    }

}
