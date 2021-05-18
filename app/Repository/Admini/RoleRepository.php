<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/8
// +----------------------------------------------------------------------

namespace App\Repository\Admini;

use App\Models\Admini\Role;
use App\Repository\SystemRepository;
use App\Repository\Searchable;

class RoleRepository extends SystemRepository
{
    use Searchable;

    protected $publicModel;
    public function __construct(Role $role)
    {
        $this->publicModel = $role;
    }

    public function lists($perPage, $condition = [])
    {
        $orderByRaw = SystemRepository::sequenceAsc();

        $data = $this->publicModel::query()
            ->select('id' , 'title' , 'intro' , 'sort' , 'status' , 'created_at' , 'updated_at')
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderByRaw($orderByRaw)
            ->paginate($perPage);

        $data->transform(function ($item) {
            xssFilter($item);
            $item->editUrl = route('admini.role.edit', ['role' => $item]);
            $item->deleteUrl = route('admini.role.destroy', ['role' => $item]);
            $item->statusText = $item->status == 1 ? '<span class="layui-badge layui-bg-green">启用</span>' : '<span class="layui-badge">禁用</span>';
            return $item;
        });

        return [
            'code' => 0,
            'msg' => '',
            'count' => $data->total(),
            'data' => $data->items(),
        ];
    }

    public function limit()
    {
        $orderByRaw = SystemRepository::sequenceAsc();

        return $this->publicModel::query()->select('id', 'title', 'status')->orderByRaw($orderByRaw)->get();
    }


}

