<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller {

    public function authenticate(Request $request) {
        // Get only email and password from request
        $credenciais = $request->only('usuario', 'senha');

        // Get user by email
        $usuario = Usuario::where('usuario', $credenciais['usuario'])->first();

        // Validate Company
        if(!$usuario) {
            return response()->json([
                'error' => 'Usuário ou senha não encontrados'
            ], 401);
        }
        // Validate Password
        if (!Hash::check($credenciais['senha'], $usuario->senha)) {
            return response()->json([
                'error' => 'Invalid credenciais'
            ], 401);
        }

        // Generate Token
        $token = JWTAuth::fromUser($usuario);

        // Get expiration time
        $objectToken = JWTAuth::setToken($token);
        $expiration = JWTAuth::decode($objectToken->getToken())->get('exp');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::decode()->get('exp')
        ]);
    }
}
