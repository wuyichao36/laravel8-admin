<?php

namespace App\Http\Controllers\Admini;
use App\Exceptions\SystemException;

class LoginController extends BaseController
{

    public function login()
    {
        $credentials = request(['username', 'password']);

        if (! $token = auth('admin')->attempt($credentials)) {
            return $this->success( ['error' => 'auth login'] , 'Authorized' ,7 );
        }

        return $this->respondWithToken($token);
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
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
