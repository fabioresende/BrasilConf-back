<?php

namespace App\Http\Controllers;

use App\Fornecedor;
use App\UsuarioAdministrador;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class FornecedorController extends Controller {

    protected function ForncedorValidator($request) {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|max:50',
            'cnpj' => 'required|unique:usuarios',
            'nome' => 'required|max:50',
            'telefone' => 'required|max:50',
            'status' => 'required',
            'id_tipo_usuario' => 'required'
        ]);
        return $validator;
    }
    
    public function buscarFornecedor() {
        $idUsuarioLogado = Auth::user()->id;
        $fornecedor = Fornecedor::where('id_usuario_adm',$idUsuarioLogado)->get();
        if (!$fornecedor) {
            return response()->json([
                'message' => 'Este usuário ainda não possui fornecedor',
            ], 404);
        }
        return response()->json($fornecedor);
    }

    public function salvarFornecedor(Request $atributos) {
        $validator = $this->fornecedorValidator($atributos);
        if($validator->fails() ) {
            return response()->json([
                'message'   => 'Erros de validacao do fornecedor',
                'erros'        => $validator->errors()
            ], 422);
        }
        $fornecedor = new Fornecedor();
        $fornecedor->fill($atributos->all());
        $idUsuarioLogado = Auth::user()->id;
        $fornecedor->setAttribute('id_usuario_adm',$idUsuarioLogado);
        $retorno = $fornecedor->save();

        return response()->json($fornecedor, 201);
    }
}
