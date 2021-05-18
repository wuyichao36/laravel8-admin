<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/8
// +----------------------------------------------------------------------

namespace App\Repository\Admini;

use Illuminate\Http\Request;
use App\Models\Admini\Admin;
use App\Repository\SystemRepository;
use App\Repository\Searchable;

class ManagerRepository
{
    use Searchable;

    public static function lists($perPage, $condition = [] , $param = [])
    {
        $data = Admin::query()
            ->select('id', 'username', 'truename', 'role_id', 'login_count', 'login_ip','login_time', 'error_count' , 'status', 'created_at', 'updated_at')
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->with('role')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $data->transform(function ($item) use ($param) {
            xssFilter($item);
            $item->role_name = $item->role ? $item->role->title : '';
            unset($item->role);
            return $item;
        });

        return [
            'count' => $data->total(),
            'data' => $data->items(),
        ];
    }

    public static function find($id)
    {
        return Admin::query()->find($id);
    }

    public static function switchStatus($param)
    {
        if(!isset($param['id']) || !isset($param['field'])|| !isset($param['value'])){
            return ['code' => 0 , 'msg' => 'Error','data' =>[] ];
        }

        $item = Admin::query()->find($param['id']);

        if($param['value'] == false || $param['value'] == 'false'){
            $value = 2;
        }else{
            $value = 1;
        }
        if(!$item)
        {
            return ['code' => 0 , 'msg' => 'Error','data' =>[] ];
        }

        $itemArr = $item->toArray();

        if($itemArr[$param['field']] == $value)
        {
            return ['code' => 2 , 'msg' => 'success','data' => $item ];
        }else{
            $data = [
                $param['field'] => $value ,
            ];
            $res = Admin::query()->where('id', $itemArr['id'])->update($data);

            if($res){
                return ['code' => 1 , 'msg' => '修改成功！','data' => $item ];
            }else{
                return ['code' => 0 , 'msg' => '修改失败，请稍后重试！','data' =>[] ];
            }
        }
    }

    public static function add($data)
    {
        $data['password'] = bcrypt($data['password']);
        return Admin::query()->create($data);
    }

    public static function update($id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        return Admin::query()->where('id', $id)->update($data);
    }

    public static function delete($id)
    {
        return Admin::destroy($id);
    }

}

