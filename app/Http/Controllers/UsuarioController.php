<?php

namespace App\Http\Controllers;

use App\TipoUsuario;
use App\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Resource;

class UsuarioController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function buscarUsuarios() {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    public function buscarUsuariosId($idUsuario) {
        $usuario = Usuario::find($idUsuario);
        if (!$usuario) {
            return response()->json([
                'message' => 'Usuário não encontrado',
            ], 404);
        }
        return response()->json($usuario);
    }

    public function salvarUsuario(Request $atributos) {
        $usuario = new Usuario();
        $usuario->fill($atributos->all());
        $retorno = $usuario->save();

        return response()->json($usuario, 201);
    }

    public function buscarTiposUsuario() {
        $tiposUsuario = DB::table('tipo_usuarios')->select('descricao')->get();
        foreach ($tiposUsuario as $tipoUsuario) {
            $descricao[] = $tipoUsuario->descricao;
        }
        return response()->json($descricao, 200);
    }
}
