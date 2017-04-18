<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequirementOverview extends Model
{
    /**
     * Define a table colum ActiveDateFrom and LastUpdate for automatic handle..
     */
    const CREATED_AT = 'ActiveDateFrom';
    const UPDATED_AT = 'LastUpdate';

    /**
     * Define a table to map a model.
     */
    protected $table = 'TestRequirementOverview';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'TestRequirementOverview_id';

    /**
     * Return assigned testCases.
     */
    public function testRequrements()
    {
        return $this->hasMany('App\Requirement', 'TestRequirementOverview_id');
    }

}
