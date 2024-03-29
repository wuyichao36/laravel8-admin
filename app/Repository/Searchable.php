<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/8
// +----------------------------------------------------------------------
namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public static function buildQuery(Builder $query, array $condition)
    {
        // 获取模型定义的搜索域
        $model = $query->getModel();
        $searchField = [];
        if (property_exists($model, 'searchField')) {
            $searchField = $model::$searchField;
        }

        foreach ($condition as $k => $v) {
            if (!is_array($v) && isset($searchField[$k]['searchType'])) {
                $condition[$k] = [$searchField[$k]['searchType'], $v];
            }
        }

        foreach ($condition as $k => $v) {
            $type = 'like';
            $value = $v;
            if (is_array($v)) {
                list($type, $value) = $v;
            }
            $value = trim($value);
            // 搜索值为空字符串则忽略该条件
            if ($value === '') {
                continue;
            }

            if ($k === 'created_at' ||
                $k === 'updated_at' ||
                (isset($searchField[$k]['showType']) && $searchField[$k]['showType'] === 'datetime')
            ) {
                $dates = explode(' ~ ', $value);
                if (count($dates) === 2) {
                    $query->whereBetween($k, [
                        Carbon::parse($dates[0])->startOfDay(),
                        Carbon::parse($dates[1])->endOfDay(),
                    ]);
                }
            } else {
                $query->where($k, $type, $type === 'like' ? "%{$value}%" : $value);
            }
        }
    }

}
