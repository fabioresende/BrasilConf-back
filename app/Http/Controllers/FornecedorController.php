<?php

namespace App\Http\Controllers;

use App\Fornecedor;
use App\UsuarioAdministrador;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FornecedorController extends Controller
{

    protected function fornecedorValidator($request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:50',
            'cnpj' => 'required',
            'cep' => 'required|max:8|min:8',
            'telefone' => 'required|max:11',
            'tipo_logradouro' => 'required|max:7',
            'logradouro' => 'required|max:50',
            'numero' => 'required',
            'url_logo' => 'required',
            'estado' => 'required',
            'cidade' => 'required'
        ]);
        return $validator;
    }

    public function buscarFornecedor()
    {
        $idUsuarioLogado = Auth::user()->id;
        $fornecedor = Fornecedor::where('id_usuario_adm', $idUsuarioLogado)->first();
        if (!$fornecedor) {
            return response()->json([
                'message' => 'Este usuário ainda não possui fornecedor',
            ], 404);
        }
        return response()->json($fornecedor);
    }

    public function salvarFornecedor(Request $atributos) {
        $validator = $this->fornecedorValidator($atributos);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erros de validacao do fornecedor',
                'erros' => $validator->errors()
            ], 422);
        }
        $fornecedor = Fornecedor::find($atributos->id);
        if (!$fornecedor) {
            $resposta = $this->salvar($atributos);
            return response()->json($resposta, 201);
        } else {
            $resposta = $this->atualizar($fornecedor,$atributos);
            return response()->json($resposta, 202);
        }


    }

    private function salvar($atributos) {
        $fornecedor = new Fornecedor();
        $fornecedor->fill($atributos->all());
        $idUsuarioLogado = Auth::user()->id;
        $fornecedor->setAttribute('id_usuario_adm', $idUsuarioLogado);
        $controle = $fornecedor->save();
        if ($controle) {
            $resposta['mensagem'] = "Fornecedor salvo com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível salvar fornecedor!";
            $resposta['success'] = false;
            return $resposta;
        }
    }

    private function atualizar($fornecedor,$atributos) {
        $fornecedor->fill($atributos->all());
        $controle = $fornecedor->save();
        if ($controle) {
            $resposta['mensagem'] = "Fornecedor atualizado com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível atualizar fornecedor!";
            $resposta['success'] = false;
            return $resposta;
        }
    }
}
