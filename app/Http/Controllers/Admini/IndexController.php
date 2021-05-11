<?php

namespace App\Http\Controllers\Admini;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admini\Admin;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function index()
    {
        return view('admini.index.index');
    }

    public function home(Request $request)
    {
        $v = "version()" ;
        $mysql_version = DB::select("select version()")[0]->$v ;

        $auth_admin = auth()->guard('admini')->user();
        $admin_item = $auth_admin::with('role')->first();

        $item = [
            'url' => $_SERVER['SERVER_NAME'] ,
            'port' => $_SERVER['SERVER_PORT'] ,
            'ip' => $request->getClientIp() ,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ,
            'php_version' => PHP_VERSION ,
            'mysql_version' => $mysql_version ,
            'php_os' => PHP_OS ,
            'safe_mode' => (boolean) ini_get('safe_mode') ?  '是' : '否' ,
            'upload_max_filesize' => @ini_get('upload_max_filesize') ,
            'date_time' => date('Y-m-d H:i:s') ,
        ];

        return view('admini.index.home' , compact('item' , 'admin_item') );
    }
}
