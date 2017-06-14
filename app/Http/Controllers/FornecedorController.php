<?php

namespace App\Http\Controllers;

use App\Http\Business\FornecedorBO;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class FornecedorController extends Controller
{
     private $fornecedorBO;
    /**
     * FornecedorController constructor.
     */
    public function __construct()
    {
        $this->fornecedorBO = new FornecedorBO();
    }

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
        $fornecedor = $this->fornecedorBO->buscarForncedor();
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
        $resposta = $this->fornecedorBO->salvar($atributos);
        return response()->json($resposta,200);
    }
}
