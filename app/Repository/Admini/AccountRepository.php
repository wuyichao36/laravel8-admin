<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/8
// +----------------------------------------------------------------------

namespace App\Repository\Admini;

use App\Models\Admini\Account;
use Illuminate\Http\Request;
use App\Repository\SystemRepository;
use App\Repository\Searchable;

class AccountRepository
{
    use Searchable;

    public static function lists($perPage, $condition = [] , $param = [])
    {
        $statusValRender = config('constants.statusValRender');
        $orderByRaw = SystemRepository::sequenceDesc($param);
        $data = Account::query()
            ->select('id', 'account', 'truename', 'role_id', 'login_count', 'login_ip','login_time', 'error_count' , 'status', 'created_at', 'updated_at')
            ->when(!empty($condition['account']) , function ($query) use ($condition)  {
                return $query->where('account' , 'like' , '%'.$condition['account'].'%' );
            })
            ->when(!empty($condition['status']) , function ($query) use ($condition)  {
                return $query->where('status' , $condition['status'] );
            })
            ->with('role')
            ->orderByRaw($orderByRaw)
            ->paginate($perPage);

        $data->transform(function ($item) use ($param,$statusValRender) {
            xssFilter($item);
            $item->role_name = $item->role ? $item->role->title : '';
            $item->status_val = $statusValRender[$item->status] ?? '-';
            unset($item->role);
            return $item;
        });
        return $data;
    }

    public static function find($id , $field = 'id')
    {
        return Account::query()->where($field,$id)->first();
    }

    public static function switchStatus($param)
    {
        if(!isset($param['id']) || !isset($param['field'])|| !isset($param['value'])){
            return ['code' => 0 , 'msg' => 'Error','data' =>[] ];
        }

        $item = self::find( $param['id'] );
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
            $res = Account::query()->where('id', $itemArr['id'])->update($data);

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
        return Account::query()->create($data);
    }

    public static function update($id, $data)
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        }

        return Account::query()->where('id', $id)->update($data);
    }

    public static function delete($id)
    {
        return Account::destroy($id);
    }

}

