<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //public $timestamps = false;

    const CREATED_AT = 'ActiveDateFrom';
    const UPDATED_AT = 'LastUpdate';

    /**
     * Define a table to map a model.
     */
    protected $table = 'SUT';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'SUT_id';

    /**
    * Get the requirement for project.
    */
    public function requirements()
    {
        return $this->hasMany('App\Requirement', 'SUT_id');
    }

}
