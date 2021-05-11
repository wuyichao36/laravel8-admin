<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Throwable;

class ParamException extends Exception
{
    // 用户错误行为触发的异常
    public function __construct(string $message = "异常错误，请稍后重试！" , $data = [] , int $code = 4 , Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function render(Request $request)
    {

        // 如果是 AJAX 请求则返回 JSON 格式的数据
        if ($request->expectsJson()) {
            return response()->json(['msg' => $this->message , 'data' => $this->data , 'code' => $this->code ] , 200 );
        }
        return view('error', ['msg' => $this->message]);
    }

}
