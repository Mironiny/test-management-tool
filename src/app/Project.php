<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * Define a table colum ActiveDateFrom and LastUpdate for automatic handle..
     */
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

    /**
     * Return assigned testCases.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'User_has_SUT', 'SUT_id', 'id')->withPivot('Role');
    }

}
