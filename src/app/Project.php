<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    /**
     * Define a table to map a model.
     */
    protected $table = 'SUT';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'SUT_id';

    /**
     * Attribute indicates actualy selected project.
     */
    protected static $selected = 0;

    /**
    * Get the requirement for project.
    */
    public function requirements()
    {
        return $this->hasMany('App\Requirement', 'SUT_id');
    }

    /**
     * Get selected project id.
     *
     * @return Project id.
     */
    public static function getSelected()
    {
        return self::$selected;
    }

    /**
     * Set selected project id.
     *
     * @var id Project id.
     */
    public static function setSelected($id)
    {
        self::$selected = $id;
    }
}
