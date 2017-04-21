<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestSuite extends Model
{
    /**
     * Define a table colum ActiveDateFrom and LastUpdate for automatic handle..
     */
    const CREATED_AT = 'ActiveDateFrom';
    const UPDATED_AT = 'LastUpdate';

    /**
     * Define a table to map a model.
     */
    protected $table = 'TestSuite';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'TestSuite_id';

    public function testCases()
    {
        return $this->hasMany('App\TestCaseOverview', 'TestSuite_id');
    }

}
