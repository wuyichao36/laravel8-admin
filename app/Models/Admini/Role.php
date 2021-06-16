<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/6
// +----------------------------------------------------------------------
namespace App\Models\Admini;

use App\Models\Base as BaseModel;

class Role extends BaseModel
{
    protected $table = 'sys_role';

    protected $fillable = [
        'title', 'intro','node_ids','status','sort','created_at', 'updated_at',
    ];

    public static $searchFieldForm = [
        'title' ,
        'intro' ,
        'status' ,
        'sort' ,
        'created_at' ,
        'updated_at' ,
    ];

}
