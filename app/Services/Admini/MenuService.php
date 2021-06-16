<?php
// +----------------------------------------------------------------------
// | Date: 2021/6/16 9:46
// +----------------------------------------------------------------------
namespace App\Services\Admini;

use App\Repository\Admini\MenuRepository;
use App\Repository\Admini\RoleRepository;
use Illuminate\Support\Facades\Log;

class MenuService
{
    public function nodeInfo($id = 0)
    {
        $menu = MenuRepository::getTree();

        $node_ids = [];
        if( !empty($id) ){
            $role = RoleRepository::find($id);
            $node_ids = $role && !empty($role['node_ids']) && $role['node_ids'] <> '[]' ? json_decode($role['node_ids'],true) : [];
        }
        Log::info('nodeInfo - '. json_encode($node_ids) );
        return self::recursionNode($menu,$node_ids);
    }

    public static function recursionNode($menu,$node_ids)
    {

        foreach($menu as &$v){
            $v['checked'] = 0;
            if($v['children']){
                foreach($v['children'] as $kk=>&$vv){
                    $vv['checked'] = 0;
                    if($node_ids && in_array($vv['id'],$node_ids)){
                        $v['checked'] = $vv['checked'] = 1;
                    }
                    if($vv['children']){
                        foreach($vv['children'] as &$s){
                            $s['checked'] = 0;
                            if($node_ids && in_array($s['id'],$node_ids)){
                                $v['checked'] = $vv['checked'] = $s['checked'] = 1;
                            }
                            if(isset($s['children'])){
                                foreach($s['children'] as &$nn){
                                    $nn['checked'] = 0;
                                    if($node_ids && in_array($nn['id'],$node_ids)){
                                        $v['checked'] = $vv['checked'] = $s['checked'] = $nn['checked'] = 1;
                                    }
                                }
                                unset($nn);
                            }
                        }
                        unset($s);
                    }
                }
                unset($vv);
            }
        }
        unset($v);
        return $menu;
    }





}
