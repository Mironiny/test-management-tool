<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequirementHistory extends Model
{
    /**
     * Define a table colum ActiveDateFrom and LastUpdate for automatic handle..
     */
    const CREATED_AT = 'ActiveDateFrom';
    const UPDATED_AT = 'LastUpdate';

    /**
     * Define a table to map a model.
     */
    protected $table = 'TestRequirementHistory';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'TestRequirement_id';

    /**
     * Return assigned testCases.
     */
    public function testCases()
    {
        return $this->belongsToMany('App\TestCaseHistory', 'TestRequirement_has_TestCase', 'TestRequirement_id', 'TestCase_id');
    }

}
