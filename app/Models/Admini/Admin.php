<?php

namespace App\Models\Admini;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'sys_administrator';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * 获取会储存到 jwt 声明中的标识
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回包含要添加到 jwt 声明中的自定义键值对数组
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['role' => 'admin'];
    }

    public static $searchField = [
        'username' => '用户名',
        'status' => [
            'showType' => 'select',
            'searchType' => '=',
            'title' => '状态',
            'enums' => [
                0 => '禁用',
                1 => '启用',
            ],
        ],
        'created_at' => [
            'showType' => 'datetime',
            'title' => '创建时间'
        ]
    ];

    public static $searchFieldForm = [
        'username' ,
        'truename' ,
        'password' ,
        'role_id' ,
        'status' ,
        'error_count' ,
        'sort' ,
        'created_at' ,
        'updated_at' ,
    ];

    public static $listField = [

        'id' => ['title' => 'ID' , 'width' => 80 , 'sort' => true],
        'username' => ['title' => '用户名' , 'minWidth' => 120],
        'truename' => ['title' => '姓名' , 'width' => 160],
        'role_name' => ['title' => '权限' , 'width' => 140],

        'status' => ['title' => '状态' , 'width' => 100 , 'sort' => true , 'templet'=> '#switchTpl'],
        'login_count' => ['title' => '登录次数' , 'width' => 110 , 'sort' => true],
        'error_count' => ['title' => '错误次数' , 'width' => 110 , 'sort' => true],
        'login_time' => ['title' => '登录时间' , 'width' => 160 , 'sort' => true],
        'login_ip' => ['title' => '登录IP', 'width' => 140 , 'sort' => true],
    ];

    public function role()
    {
        return $this->belongsTo(Role::class ,'role_id' ,'id'  );
    }

}
