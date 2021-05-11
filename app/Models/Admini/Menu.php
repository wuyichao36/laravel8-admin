<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/6
// +----------------------------------------------------------------------
namespace App\Models\Admini;

use App\Models\Base as BaseModel;

class Menu extends BaseModel
{
    protected $table = 'sys_menu';

    protected $fillable = [
        'title' ,
        'url' ,
        'parent_id',
        'type',
        'icon',
        'status' ,
        'child' ,
        'sort' ,
        'created_at' ,
        'updated_at' ,
    ];

    public static $searchField = [
        'title' => '菜单名称',
    ];

    public static $searchFieldForm = [
        'title' ,
        'url' ,
        'parent_id',
        'type',
        'icon',
        'status' ,
        'created_at' ,
        'updated_at' ,
    ];

    public static $listField = [

        'id' => ['title' => 'ID' , 'width' => 80 , 'sort' => true],
        'title' => ['title' => '菜单名称' , 'width' => 200],
        'url' => ['title' => '路由' , 'minWidth' => 280],

        'status' => ['title' => '主菜单' , 'width' => 120 , 'sort' => true , 'templet'=> '#switchTpl'],
        'sort' => ['title' => '排序' , 'width' => 100 , 'sort' => true],
        'updated_at' => ['title' => '更新时间' , 'width' => 160 , 'sort' => true],
    ];

    public function childCategory() {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    public function allChildrenCategorys()
    {
        return $this->childCategory()->with('allChildrenCategorys');
    }

}
