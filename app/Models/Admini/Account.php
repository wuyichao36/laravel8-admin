<?php
namespace App\Models\Admini;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DateTimeInterface;

class Account extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected $table = 'sys_account';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'account', 'password','truename','role_id', 'mobile','email',  'intro', 'status', 'error_count','sort','created_at', 'updated_at',
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

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public static $searchFieldForm = [
        'account',
        'truename',
        'password',
        'role_id',
        'mobile',
        'email',
        'intro',
        'status',
        'error_count',
        'sort',
        'created_at',
        'updated_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class ,'role_id' ,'id'  );
    }

}
