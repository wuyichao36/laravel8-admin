<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{

    /**
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['name', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->success( ['error' => 'auth login'] , '帐号密码不正确' ,2 );
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return $this->success( auth('api')->user() );
    }

    public function logout()
    {
        auth('api')->logout();

        return $this->success([] , 'Successfully logged out' );
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->success([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
