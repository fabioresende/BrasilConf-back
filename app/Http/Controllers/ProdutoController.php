<?php

namespace App\Http\Controllers;

use App\Http\Business\ProdutoBO;
use App\Http\Business\UsuarioBO;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    private $produtoBO;

    public function __construct() {
        $this->produtoBO = new produtoBO();
        $this->usuarioBO = new usuarioBO();
    }

    protected function produtoValidator($request) {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:50',
            'preco' => 'required',
            'validade' => 'required',
            'fabricante' => 'required|max:50',
            'descricao' => 'required',
            'status' => 'required',
            'quantidade' => 'required',
            'largura' => 'required',
            'altura' => 'required'
        ]);

        return $validator;
    }
    public function buscarProdutos() {
        $response = $this->produtoBO->buscarTodosProdutos();
        return response()->json($response);
    }

    public function buscarProdutosId($idProduto) {
        $response = $this->produtoBO->buscarProduto($idProduto);
        if (!$response) {
            return response()->json([
                'message' => 'Produto não encontrado',
            ], 404);
        }
        return response()->json($response);
    }

    public function salvarProduto(Request $atributos) {
        $validator = $this->produtoValidator($atributos);
        if($validator->fails() ) {
            return response()->json([
                'message'   => 'Erros de validacao do produto',
                'erros'        => $validator->errors()
            ], 422);
        }
        $response = $this->produtoBO->salvar($atributos);
        return response()->json($response,200);
    }
}
