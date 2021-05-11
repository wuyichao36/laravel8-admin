<?php
// +----------------------------------------------------------------------
// | Date: 2021/05/01
// +----------------------------------------------------------------------
namespace App\Http\Controllers;

use App\Exceptions\SystemException;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    public function success($data = [] , $msg = 'success' , $code = 1)
    {
        throw new SystemException($msg , $data , $code);
    }

}
