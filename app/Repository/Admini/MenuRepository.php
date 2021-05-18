<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/8
// +----------------------------------------------------------------------

namespace App\Repository\Admini;

use App\Models\Admini\Menu;
use App\Repository\SystemRepository;
use App\Repository\Searchable;
use Illuminate\Support\Facades\DB;

class MenuRepository extends SystemRepository
{
    use Searchable;

    protected $publicModel;
    public function __construct(Menu $menu)
    {
        $this->publicModel = $menu;
    }

    public function lists($perPage, $condition = [])
    {

        $orderByRaw = SystemRepository::sequenceAsc();

        $data = $this->publicModel::query()
            ->select('id', 'title', 'url', 'sort' ,'parent_id', 'status', 'type', 'created_at', 'updated_at')
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderByRaw($orderByRaw)
            ->get();

        $data->transform(function ($item) use ($perPage) {
            xssFilter($item);
            $item->editUrl = route('admini.menu.edit', ['menu' => $item] );
            $item->deleteUrl = route('admini.menu.destroy', ['menu' => $item] );
            return $item;
        });

        return [
            'code' => 0,
            'msg' => '',
            'count' => count($data),
            'data' => $data,
        ];
    }

    public function getTree()
    {
        $data = $this->publicModel::query()
            ->select('id' , 'title as name' , 'parent_id' , 'sort' , 'status', 'updated_at' )
            ->where('type' , '!=' , 2 )
            ->orderByRaw( SystemRepository::sequenceAsc() )
            ->get();

        return getGenerateTree($data->toArray());
    }



}

