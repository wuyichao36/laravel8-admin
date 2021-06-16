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
        'url_key' ,
        'authority' ,
        'parent_id',
        'type',
        'icon',
        'status' ,
        'sort' ,
        'created_at' ,
        'updated_at' ,
    ];

    public static $searchFieldForm = [
        'title' ,
        'url' ,
        'url_key' ,
        'authority' ,
        'parent_id',
        'type',
        'icon',
        'status' ,
        'created_at' ,
        'updated_at' ,
    ];

    public function childCategory() {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->childCategory()->with('children');
    }

}
