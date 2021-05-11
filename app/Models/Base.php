<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/4
// +----------------------------------------------------------------------
namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Base extends EloquentModel
{
    use SoftDeletes;

    //const DELETED_AT='deleted_at';
    #set the date format as timestamp
    //protected $dateFormat='U';
}
