<?php

namespace App\Http\Controllers\Admini;
use App\Exceptions\SystemException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends BaseController
{

    public function login(Request $request)
    {
        $credentials = $request->json()->all();//request(['account', 'password']);
        if (! $token = auth('admin')->attempt($credentials)) {
            return $this->success( ['error' => 'auth login'] , '帐号密码不正确' ,2 );
        }

        $result['permissions'] = [['id'=> 'queryForm', 'operation'=> ['add', 'edit']]];
        $result['roles'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $result['token'] = [
            'token' => 'Bearer '. $token,
            'type' => 'Bearer',
            'expires_in' => (int)(time() + auth('api')->factory()->getTTL() * 60)
        ];
        $result['info'] = auth('admin')->user();
        $username = $result['info']['account'] ?? '-';

        //$result['info']['roles'] = $roles;

        return $this->success($result,$username .'，欢迎回来');
    }

    public function me()
    {
        return $this->success(auth('admin')->user());
    }

    public function logout()
    {
        auth('admin')->logout();

        return $this->success([] , 'Successfully logged out' );
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->success([
            'token' => $token,
            'type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
