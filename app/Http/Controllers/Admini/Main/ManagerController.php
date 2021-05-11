<?php

namespace App\Http\Controllers\Admini\Main;

use App\Http\Controllers\Admini\CommonController;
use App\Http\Requests\Admini\AdminRequest;
use App\Models\Admini\Admin;
use App\Repository\Admini\ManagerRepository;
use App\Repository\Admini\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class ManagerController extends CommonController
{
    //
    protected $roleList;
    public function __construct()
    {
        $this->roleList = RoleRepository::limit();
    }

    public function index()
    {
        return view('admini.main.manager.index');
    }

    public function lists(Request $Request)
    {
        $param = $Request->getQueryString();

        $pageRows = (isset($Request->limit) && $Request->limit > 0 ) ? $Request->limit : config('constants.pageRows');

        $condition = $Request->only(Admin::$searchFieldForm);
        $data = ManagerRepository::lists($pageRows , $condition , $param);

        return $data;
    }

    public function switch_edit(Request $Request)
    {
        try {
            $res = ManagerRepository::switchStatus($Request->post());
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
        //$roleList = RoleRepository::limit();
        return view('admini.main.manager.add' , ['roleList' => $this->roleList]);
    }

    /**
     */
    public function store(AdminRequest $Request)
    {
        try {
            $data = $Request->only(Admin::$searchFieldForm);
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }

            ManagerRepository::add($data);
            return [
                'code' => 1,
                'msg' => '添加成功',
                'redirect' => route('admini.manager.index')
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
        $model = ManagerRepository::find($id);
        $roleList = RoleRepository::limit();

        return view('admini.main.manager.add', ['id' => $id, 'model' => $model , 'roleList' => $this->roleList] );
    }

    /**
     */
    public function update(AdminRequest $Request, $id)
    {

        try {
            $data = $Request->only(Admin::$searchFieldForm);
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }
            if ($Request->input('password') == '') {
                unset($data['password']);
            }

            ManagerRepository::update($id, $data);
            return [
                'code' => 1,
                'msg' => '编辑成功',
                'redirect' => route('admini.manager.index') .'?'. $Request->getQueryString()
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
            ManagerRepository::delete($id);
            return [
                'code' => 1,
                'msg' => '删除成功',
                'redirect' => route('admini.manager.index') .'?'. $Request->getQueryString()
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
