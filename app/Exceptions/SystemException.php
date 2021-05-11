<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Throwable;

class SystemException extends Exception
{
    // 系统内部异常
    public function __construct(string $message = "系统内部错误！" , $data = [] , int $code = 11 , Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function render(Request $request)
    {

        return response()->json(['msg' => $this->message , 'data' => $this->data , 'code' => $this->code ] , 200 );
    }

}
