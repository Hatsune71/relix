<?php

namespace App\Http\Controllers;

use Laravel\Passport\TokenRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel;
use Illuminate\Http\Request;

class AuthController extends DasarController
{
    public function login(Request $request){
        $validator = validator($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return $this->response($validator->errors(), 401);
        }
        $user = UsersModel::where('email', $request['email'])->first();
        if ($user) {
            if (Hash::check($request['password'], $user->password)) {
                $token = $user->createToken('Relix Token')->accessToken;
                $data = ['token' => $token];
                return $this->response($data, 200);
            } else {
                $data = "Password atau username salah";
                return $this->response($data, 401);
            }
        } else {
            $data = 'Password atau username salah';
            return $this->response($data, 401);
        }
    }

    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6',
            'repassword' => 'required|same:password'
        ]);

        if ($validator->fails())
        {
            return $this->response($validator->errors(), 500);
        }

        $request['password']=Hash::make($request['password']);
        $user = UsersModel::create($request->toArray());
        
        $user = UsersModel::where('email', $user['email'])->first();
        $token = $user->createToken('Relix Token')->accessToken;

        $data = ['token' => $token];
        return $this->response($data, 200);
    }

    public function logout (Request $request) {
        $token = $request->bearerToken();
        app(TokenRepository::class)->revokeAccessToken($token);
        $data = 'Logout berhasil';
        return $this->response($data, 200);
    }

    public function checkAuth()
    {
        return $this->response('Auth Sukses', 200);
    }
}