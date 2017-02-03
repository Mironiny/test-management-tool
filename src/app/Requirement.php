<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    /**
     * Define a table to map a model.
     */
    protected $table = 'TestRequirement';

    /**
     * Define primary key of table.
     */
    protected $primaryKey = 'TestRequirement_id';

}
