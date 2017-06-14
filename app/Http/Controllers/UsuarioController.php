<?php

namespace App\Http\Controllers;

use App\Http\Business\UsuarioBO;
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
    private $usuarioBO;

    public function __construct() {
        $this->usuarioBO = new UsuarioBO();
    }

    protected function usuarioValidator($request) {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|max:50',
            'cpf' => 'required|max:11',
            'nome' => 'required|max:50',
            'telefone' => 'required|max:50',
            'status' => 'required',
            'id_tipo_usuario' => 'required'
        ]);

        return $validator;
    }
    public function buscarUsuarios() {
        $usuarioAdm = $this->usuarioBO->buscarUsuarioAdm();
        $response = $this->usuarioBO->buscarTodosUsuarios($usuarioAdm->id);
        return response()->json($response);
    }

    public function buscarUsuariosId($idUsuario) {
        $response = $this->usuarioBO->buscarUsuario($idUsuario);
        if (!$response) {
            return response()->json([
                'message' => 'Usuário não encontrado',
            ], 404);
        }
        return response()->json($response);
    }

    public function salvarUsuario(Request $atributos) {
        $validator = $this->usuarioValidator($atributos);
        if($validator->fails() ) {
            return response()->json([
                'message'   => 'Erros de validacao do usuario',
                'erros'        => $validator->errors()
            ], 422);
        }
        $response = $this->usuarioBO->salvar($atributos);
        return response()->json($response,200);
    }

    public function buscarTiposUsuario() {
        $response = $this->usuarioBO->buscarTiposUsuario();
        return response()->json($response, 200);
    }
}
