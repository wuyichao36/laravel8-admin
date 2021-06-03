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

    public static function lists($perPage, $condition = [])
    {
        $orderByRaw = SystemRepository::sequenceAsc();
        $data = Role::query()
            ->select('id' , 'title' , 'intro' , 'sort' , 'status' , 'created_at' , 'updated_at')
            ->where(function ($query) use ($condition) {
                Searchable::buildQuery($query, $condition);
            })
            ->orderByRaw($orderByRaw)
            ->paginate($perPage);

        $data->transform(function ($item) {
            xssFilter($item);
            return $item;
        });

        return $data;
    }

    public function limit()
    {
        $orderByRaw = SystemRepository::sequenceAsc();

        return Role::query()->select('id', 'title', 'status')->orderByRaw($orderByRaw)->get();
    }


}

