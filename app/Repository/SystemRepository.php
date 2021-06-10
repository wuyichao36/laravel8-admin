<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/8
// +----------------------------------------------------------------------
namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SystemRepository
{

    public static function sequenceAsc($condition = [])
    {
        $sequence = DB::raw('sort desc,id asc');
        if( !empty($condition['orderKey']) && !empty($condition['orderBy']) ){
            $orderBy = $condition['orderBy'] == 'normal' ? '' : ' '.$condition['orderBy'];
            $orderKey = rtrim($condition['orderKey'],'_val');
            $sequence = DB::raw($orderKey . $orderBy );
        }
        return $sequence;
    }

    public static function sequenceDesc($condition = [])
    {
        $sequence = DB::raw('sort desc,id desc');
        if( !empty($condition['orderKey']) && !empty($condition['orderBy']) ){
            $orderBy = $condition['orderBy'] == 'normal' ? '' : ' '.$condition['orderBy'];
            $orderKey = rtrim($condition['orderKey'],'_val');
            $sequence = DB::raw($orderKey . $orderBy );
        }

        return $sequence;
    }

    public function find($id,$model)
    {
        return $model::query()->find($id);
    }

    public function switchStatus($param,$model)
    {
        if(!isset($param['id']) || !isset($param['field'])|| !isset($param['value'])){
            return ['code' => 0 , 'msg' => 'Error','data' =>[] ];
        }

        $item = $model::query()->find($param['id']);

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

        if(isset($itemArr[$param['field']]) && $itemArr[$param['field']] == $value)
        {
            return ['code' => 2 , 'msg' => 'success','data' => $item ];
        }else{
            $data = [
                $param['field'] => $value ,
            ];
            $res = $model::query()->where('id', $itemArr['id'])->update($data);

            if($res){
                return ['code' => 1 , 'msg' => '修改成功！','data' => $item ];
            }else{
                return ['code' => 0 , 'msg' => '修改失败，请稍后重试！','data' =>[] ];
            }
        }
    }

    public function addItem($data,$model)
    {
        return $model::query()->create($data);
    }

    public function updateItem($id, $data,$model)
    {
        return $model::query()->where('id', $id)->update($data);
    }

    public function deleteItem($id,$model)
    {
        return $model::destroy($id);
    }
}
