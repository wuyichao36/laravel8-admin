<?php

namespace App\Http\Controllers\Admini\Base;

use App\Http\Controllers\Admini\CommonController;
use App\Http\Requests\Admini\MenuRequest;
use App\Models\Admini\Menu;
use App\Repository\Admini\MenuRepository;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class MenuController extends CommonController
{
    protected $menuRepository;
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;

        parent::__construct();
    }

    //
    public function index()
    {
        return view('admini.main.menu.index');
    }

    public function lists(Request $request)
    {
        $param = $request->getQueryString();
        $pageRows = (isset($request->limit) && $request->limit > 0 ) ? $request->limit : config('constants.pageRows');

        $condition = $request->only(Menu::$requestForm);
        $data = $this->menuRepository->lists($pageRows , $condition , $param);

        //return view('admini.main.menu.index');
        return $data;
    }

    public function switch_edit(Request $request)
    {
        try {
            $res = $this->menuRepository->switchStatus($request->post());
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

    public function load_tree(Request $request)
    {
        $result = MenuRepository::getTree();

        return $this->success($result);
    }

    public function icon()
    {
        return view('admini.main.menu.icon');
    }

    /**
     */
    public function create(Request $request)
    {
        $model = (object)[
            'parent_id' => $request->id ?? 0,
            'type' => $request->menu ?? 0,
        ];

        return view('admini.main.menu.add', ['model' => $model,] );
    }

    /**
     */
    public function store(MenuRequest $request)
    {
        try {
            $data = $request->only(Menu::$requestForm);
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }

            $this->menuRepository->addItem($data);
            return [
                'code' => 1,
                'msg' => '添加成功',
                'redirect' => route('admini.menu.index')
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
        $model = $this->menuRepository->find($id);

        return view('admini.main.menu.add', ['id' => $id, 'model' => $model] );
    }

    /**
     */
    public function update(MenuRequest $request, $id)
    {
        try {
            $data = $request->only(Menu::$requestForm);
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }
            $this->menuRepository->updateItem($id, $data);
            return [
                'code' => 1,
                'msg' => '编辑成功',
                'redirect' => route('admini.menu.index') .'?'. $request->getQueryString()
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
    public function destroy(Request $request,$id)
    {
        sleep(500);
        exit;
        try {
            $this->menuRepository->deleteItem($id);
            return [
                'code' => 1,
                'msg' => '删除成功',
                'redirect' => route('admini.menu.index') .'?'. $request->getQueryString()
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
