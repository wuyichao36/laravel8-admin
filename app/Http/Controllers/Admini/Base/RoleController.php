<?php

namespace App\Http\Controllers\Admini\Base;

use App\Http\Controllers\Admini\BaseController;
use App\Models\Admini\Role;
use App\Repository\Admini\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class RoleController extends BaseController
{

    public function index(Request $Request)
    {
        $param = $Request->getQueryString();

        $pageRows = (isset($Request->limit) && $Request->limit > 0 ) ? $Request->limit : config('constants.pageRows');

        $condition = $Request->only(Role::$searchFieldForm);
        $data = RoleRepository::lists($pageRows , $condition , $param);

        return $this->success($data);
    }

    public function lists(Request $Request)
    {
        $data = RoleRepository::limit();
        return $this->success($data);
    }

    public function switch_edit(Request $Request)
    {
        try {
            $res = RoleRepository::switchStatus($Request->post());
            return [
                'code' => $res['code'],
                'msg' => $res['msg'],
                'redirect' => []
            ];
        } catch (QueryException $e) {
            return [
                'code' => 0,
                'msg' => '修改失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前记录已存在' : '其它错误'),
                'redirect' => []
            ];
        }
    }

    public function show(Request $request)
    {
        $id = $request->query('id') ?? 0;
        try {
            $res = RoleRepository::find($id);
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

    public function store(Request $request)
    {
        $data = $request->only(Role::$searchFieldForm);
        $id = $request->post('id') ?? 0;

        if( empty($id) ) {
            try {
                $this->roleRepository->addItem($data);
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
                $this->roleRepository->updateItem($id, $data);
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

    /**
     */
    public function destroy(Request $Request,$id)
    {
        try {
            $this->roleRepository->deleteItem($id);
            return [
                'code' => 1,
                'msg' => '删除成功',
                'redirect' => route('admini.role.index') .'?'. $Request->getQueryString()
            ];
        } catch (\RuntimeException $e) {
            return [
                'code' => 0,
                'msg' => '删除失败：' . $e->getMessage(),
                'redirect' => false
            ];
        }
    }


}
