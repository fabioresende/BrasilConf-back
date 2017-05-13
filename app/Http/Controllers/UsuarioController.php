<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;

class UsuarioController extends Controller
{
    public function buscarUsuarios() {
        $users = Usuario::all();
        return response()->json($users);
    }

    public function buscarUsuariosId($idusuario) {
        $usuario = Usuario::find($idusuario);
        if(!$usuario){
            return response()->json([
                'message'   => 'Usuário não encontrado',
            ], 404);
        }
        return response()->json($usuario);
    }
}
