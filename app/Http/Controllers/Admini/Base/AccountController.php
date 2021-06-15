<?php

namespace App\Http\Controllers\Admini\Base;

use App\Http\Controllers\Admini\BaseController;
use App\Models\Admini\Account;
use App\Repository\Admini\AccountRepository;
use App\Repository\Admini\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AccountController extends BaseController
{
    //
    public function index(Request $request)
    {
        $param = $request->getQueryString();
        $pageRows = (isset($request->size) && $request->size > 0 ) ? $request->size : config('constants.pageRows');

        $condition = $request->only(Account::$searchFieldForm);
        $data = AccountRepository::lists($pageRows , $condition , $param);

        return $this->success($data);
    }

    public function show(Request $request)
    {
        $id = $request->query('id') ?? 0;
        try {
            $res = AccountRepository::find($id);
            $item = [
                'code' => 1,
                'msg' => 'success',
                'data' => $res
            ];
        } catch (QueryException $e) {
            $item = [
                'code' => 0,
                'msg' => '修改失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前记录已存在' : '其它错误'),
                'data' => []
            ];
        }
        return $this->success($item['data'] ?? [] , $item['msg'] ?? '其它错误' , $item['code'] ?? 0 );
    }

    public function store_update(Request $request)
    {
        $data = $request->only(Account::$searchFieldForm);
        $id = $request->post('id') ?? 0;
        if( empty($id) )
        {
            try {
                if (!isset($data['status']))
                {
                    $data['status'] = 1;
                }
                AccountRepository::add($data);
                $item = [
                    'code' => 1,
                    'msg' => '添加成功',
                    'data' => []
                ];
            } catch (QueryException $e) {
                $item = [
                    'code' => 0,
                    'msg' => '添加失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前记录已存在' : '其它错误'),
                    'data' => []
                ];
            }
        }else{
            try {
                if ( !isset($data['status']) )
                {
                    $data['status'] = 1;
                }
                if ( empty($data['password']) )
                {
                    unset($data['password']);
                }
                AccountRepository::update($id, $data);

                $item = [
                    'code' => 1,
                    'msg' => '编辑成功',
                    'data' => []
                ];
            } catch (QueryException $e) {
                $item = [
                    'code' => 0,
                    'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前记录已存在' : '其它错误'),
                    'data' => []
                ];
            }
        }
        return $this->success($item['data'] ?? [] , $item['msg'] ?? '其它错误' , $item['code'] ?? 0 );
    }

    public function password(Request $request)
    {
        try {
            $data = $request->only(Account::$searchFieldForm);
            $id = $request->post('id') ?? 0;

            AccountRepository::update($id, $data);
            $item = [
                'code' => 1,
                'msg' => '重置密码成功',
                'data' => []
            ];
        } catch (QueryException $e) {
            $item = [
                'code' => 0,
                'msg' => '重置失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '重置密码错误' : '其它错误'),
                'data' => []
            ];
        }

        return $this->success($item['data'] ?? [] , $item['msg'] ?? '其它错误' , $item['code'] ?? 0 );
    }

    public function destroy(Request $request)
    {
        $id = $request->post('id') ?? 0;
        try {
            AccountRepository::delete( json_decode($id) );
            $item = [
                'code' => 1,
                'msg' => '删除成功',
                'redirect' => []
            ];
        } catch (QueryException $e) {
            $item = [
                'code' => 0,
                'msg' => '删除失败：' . $e->getMessage(),
                'redirect' => []
            ];
        }

        return $this->success($item['data'] ?? [] , $item['msg'] ?? '其它错误' , $item['code'] ?? 0 );
    }


}
