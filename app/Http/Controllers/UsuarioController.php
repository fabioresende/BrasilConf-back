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
        $usuario = Usuario::find($atributos->id);
        if (!$usuario) {
            $resposta = $this->salvar($atributos);
            return response()->json($resposta, 201);
        }
        else {
            $resposta = $this->atualizar($usuario,$atributos);
            return response()->json($resposta, 202);
        }
    }

    public function buscarTiposUsuario() {
        $tiposUsuario = DB::table('tipo_usuarios')->select('descricao')->get();
        foreach ($tiposUsuario as $tipoUsuario) {
            $descricao[] = $tipoUsuario->descricao;
        }
        return response()->json($descricao, 200);
    }

    private function salvar($atributos) {
        $usuario = new Usuario();
        $usuario->fill($atributos->all());
        $retorno = $usuario->save();
        if ($retorno) {
            $resposta['mensagem'] = "Usuário salvo com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível salvar usuário!";
            $resposta['success'] = false;
            return $resposta;
        }
    }

    private function atualizar($usuario,$atributos) {
        $usuario->fill($atributos->all());
        $controle = $usuario->save();
        if ($controle) {
            $resposta['mensagem'] = "Usuário atualizado com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível atualizar usuário!";
            $resposta['success'] = false;
            return $resposta;
        }
    }
}
