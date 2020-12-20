<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            // 'numero'=>'required|string|mim:9'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Usuário registrado com sucesso',
            'user' => $user
        ], 201);
    }




    protected function createNewToken($token)
    {
        return response()->json([
            'message' => 'ok',
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'nome' => auth()->user()->name,


        ]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'O e-mail ou a senha estão errado.'], 401);
        }
        return $this->createNewToken($token);
    }


    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Usuário desconectado com sucesso']);
    }


    public function userProfile()
    {
        return response()->json(auth()->user());
    }
}
