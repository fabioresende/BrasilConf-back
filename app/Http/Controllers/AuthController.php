<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutenticateRequest;
use App\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function authenticate(Request $request)
    {
        // Get only email and password from request
        $credenciais = $request->only('usuario', 'senha');
        // Get user by email
        $usuario = Usuario::where('usuario', $credenciais['usuario'])->first();
        // Validate Company
        if (!$usuario) {
            return response()->json([
                'success' => 'false',
                'msg' => 'Usuário ou senha não encontrados'
            ], 401);
        }
        // Validate Password
        if ($credenciais['senha'] != $usuario->senha) {
            return response()->json([
                'success' => 'false',
                'msg' => 'Usuário ou senha não encontrados'
            ], 401);
        }
        // Generate Token
        $token = JWTAuth::fromUser($usuario);
        // Get expiration time
        $objectToken = JWTAuth::setToken($token);
        $expiration = JWTAuth::decode($objectToken->getToken())->get('exp');
        if (!$token) {
            return response()->json([
                'success' => false,
                'msg' => 'Não foi possivel fazer o login'
            ], 500);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }

    public function buscarUsuarioLogado(){
        $usuario = JWTAuth::toUser();
        if (!$usuario) {
            return response()->json([
                'error' => 'Não foi possivel encontrar o usuário logado'
            ], 500);
        }
        return response()->json($usuario);
    }
}
