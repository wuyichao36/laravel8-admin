<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/4
// +----------------------------------------------------------------------
namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Base extends EloquentModel
{
    use SoftDeletes;

    const DELETED_AT='deleted_at';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
