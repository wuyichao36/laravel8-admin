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

    public function lists(Request $Request)
    {
        $param = $Request->getQueryString();

        $pageRows = (isset($Request->limit) && $Request->limit > 0 ) ? $Request->limit : config('constants.pageRows');

        $condition = $Request->only(Role::$searchFieldForm);
        $data = RoleRepository::lists($pageRows , $condition , $param);

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

    /**
     */
    public function create()
    {
        return view('admini.main.role.add');
    }

    /**
     */
    public function store(RoleRequest $Request)
    {
        try {
            $this->roleRepository->addItem($Request->only(Role::$searchFieldForm) );
            return [
                'code' => 1,
                'msg' => '添加成功',
                'redirect' => route('admini.role.index')
            ];
        } catch (QueryException $e) {
            return [
                'code' => 0,
                'msg' => '添加失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前记录已存在' : '其它错误'),
                'redirect' => false
            ];
        }
    }

    /**
     */
    public function edit($id)
    {
        $model = $this->roleRepository->find($id);

        return view('admini.main.role.add', ['id' => $id, 'model' => $model] );
    }

    /**
     */
    public function update(RoleRequest $Request, $id)
    {

        $data = $Request->only(Role::$searchFieldForm);

        try {
            $this->roleRepository->updateItem($id, $data);
            return [
                'code' => 1,
                'msg' => '编辑成功',
                'redirect' => route('admini.role.index') .'?'. $Request->getQueryString()
            ];
        } catch (QueryException $e) {
            return [
                'code' => 0,
                'msg' => '编辑失败：' . (Str::contains($e->getMessage(), 'Duplicate entry') ? '当前记录已存在' : '其它错误'),
                'redirect' => false
            ];
        }
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
