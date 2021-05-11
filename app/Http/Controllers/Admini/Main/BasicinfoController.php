<?php

namespace App\Http\Controllers\Admini\Main;

use App\Http\Controllers\Admini\CommonController;
use Illuminate\Http\Request;

class BasicinfoController extends CommonController
{
    //
    public function index()
    {
        return view('admini.main.basicinfo.index');
    }


}
