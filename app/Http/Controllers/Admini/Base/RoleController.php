<?php

namespace App\Http\Controllers\Admini\Base;

use App\Http\Controllers\Admini\BaseController;
use App\Models\Admini\Role;
use App\Repository\Admini\RoleRepository;
use App\Services\Admini\MenuService;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class RoleController extends BaseController
{

    public function index(Request $request)
    {
        $param = $request->getQueryString();
        $pageRows = (isset($request->limit) && $request->limit > 0 ) ? $request->limit : config('constants.pageRows');

        $condition = $request->only(Role::$searchFieldForm);
        $data = RoleRepository::lists($pageRows , $condition , $param);

        return $this->success($data);
    }

    public function lists(Request $request)
    {
        $data = RoleRepository::limit();
        return $this->success($data);
    }

    public function show(Request $request,MenuService $menuService)
    {
        $id = $request->query('id') ?? 0;
        try {
            $res = RoleRepository::find($id);
            $res['node_info'] = $menuService->nodeInfo($id);

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
        $data = $request->only(Role::$searchFieldForm);
        $id = $request->post('id') ?? 0;

        $params = $request->json()->all();

        $node_ids = !empty($params['node_info']) ? $params['node_info'] : [];
        $data['node_ids'] = json_encode($node_ids);

        if( empty($id) )
        {
            try {
                if (!isset($data['status']))
                {
                    $data['status'] = 1;
                }
                RoleRepository::add($data);
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
                RoleRepository::update($id, $data);
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
    public function destroy(Request $request)
    {
        $id = $request->post('id') ?? 0;
        try {
            RoleRepository::delete( json_decode($id) );
            $item = [
                'code' => 1,
                'msg' => '删除成功',
                'data' => []
            ];
        } catch (QueryException $e) {
            $item = [
                'code' => 0,
                'msg' => '删除失败：' . $e->getMessage(),
                'data' => []
            ];
        }

        return $this->success($item['data'] ?? [] , $item['msg'] ?? '其它错误' , $item['code'] ?? 0 );
    }


}
