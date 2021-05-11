<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/6
// +----------------------------------------------------------------------
namespace App\Models\Admini;

use App\Models\Base as BaseModel;

class Role extends BaseModel
{
    protected $table = 'sys_role';

    public static $searchField = [
        'title' => '角色名称',
    ];

    public static $searchFieldForm = [
        'title' ,
        'intro' ,
        'status' ,
        'sort' ,
        'created_at' ,
        'updated_at' ,
    ];

    public static $listField = [

        'id' => ['title' => 'ID' , 'width' => 80 , 'sort' => true],
        'title' => ['title' => '角色名称' , 'width' => 200],
        'intro' => ['title' => '简述' , 'minWidth' => 280],

        'status' => ['title' => '状态' , 'width' => 100 , 'sort' => true , 'templet'=> '#switchTpl'],
        'updated_at' => ['title' => '更新时间' , 'width' => 160 , 'sort' => true],
    ];
}
