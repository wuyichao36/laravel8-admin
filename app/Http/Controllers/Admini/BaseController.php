<?php
// +----------------------------------------------------------------------
// | Date: 2020/6/5
// +----------------------------------------------------------------------
namespace App\Http\Controllers\Admini;

use App\Exceptions\SystemException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{

    public function success($data = [] , $msg = 'success' , $code = 1)
    {

        throw new SystemException($msg , $data , $code);
    }

}
