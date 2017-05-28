<?php

namespace App\Http\Controllers;

use App\TipoUsuario;
use App\Usuario;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Resource;

class UsuarioController extends Controller
{
    public function __construct() {
    }

    protected function usuarioValidator($request) {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|max:50',
            'cpf' => 'required|unique:usuarios',
            'nome' => 'required|max:50',
            'telefone' => 'required|max:50',
            'status' => 'required',
            'id_tipo_usuario' => 'required'
        ]);

        return $validator;
    }
    public function buscarUsuarios() {
        //$teste = $request->getUserInfo();
        //print_r(Auth::user()->id);die();
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
        $validator = $this->usuarioValidator($atributos);
        if($validator->fails() ) {
            return response()->json([
                'message'   => 'Erros de validacao do usuario',
                'erros'        => $validator->errors()
            ], 422);
        }
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
