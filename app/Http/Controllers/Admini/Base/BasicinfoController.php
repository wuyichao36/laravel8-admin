<?php

namespace App\Http\Controllers\Admini\Base;

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
