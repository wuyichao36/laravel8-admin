<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/8
// +----------------------------------------------------------------------
namespace App\Repository\Admini;

use App\Models\Admini\Role;
use App\Repository\Searchable;
use App\Repository\SystemRepository;

class RoleRepository
{
    use Searchable;

    public static function lists($perPage, $condition = [] , $param = [])
    {
        $statusValRender = config('constants.statusValRender');
        $orderByRaw = SystemRepository::sequenceAsc($param);
        $data = Role::query()
            ->select('id' , 'title' , 'intro' , 'sort' , 'status' , 'created_at' , 'updated_at')
            ->when(!empty($condition['title']) , function ($query) use ($condition)  {
                return $query->where('title' , 'like' , '%'.$condition['title'].'%' );
            })
            ->when(!empty($condition['status']) , function ($query) use ($condition)  {
                return $query->where('status' , $condition['status'] );
            })
            ->orderByRaw($orderByRaw)
            ->paginate($perPage);

        $data->transform(function ($item) use ($statusValRender)  {
            xssFilter($item);
            $item->status_val = $statusValRender[$item->status] ?? '-';
            return $item;
        });

        return $data;
    }

    public static function limit()
    {
        $orderByRaw = SystemRepository::sequenceAsc();

        return Role::query()->select('id', 'title', 'status')->orderByRaw($orderByRaw)->get();
    }

    public static function find($id , $field = 'id')
    {
        return Role::query()->where($field,$id)->first();
    }

    public static function add($data)
    {
        return Role::query()->create($data);
    }

    public static function update($id, $data)
    {
        return Role::query()->where('id', $id)->update($data);
    }

    public static function delete($id)
    {
        return Role::destroy($id);
    }

}

