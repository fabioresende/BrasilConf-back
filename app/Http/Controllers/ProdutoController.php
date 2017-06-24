<?php

namespace App\Http\Controllers;

use App\Http\Business\FornecedorBO;
use App\Http\Business\LojaBO;
use App\Http\Business\ProdutoBO;
use App\Http\Business\UsuarioBO;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ProdutoController extends Controller
{
    private $produtoBO;
    private $fornecedorBO;
    public function __construct() {
        $this->produtoBO = new ProdutoBO();
        $this->usuarioBO = new UsuarioBO();
        $this->fornecedorBO = new FornecedorBO();
        $this->lojaBO = new lojaBO();
    }

    protected function produtoValidator($request) {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|max:50',
            'preco' => 'required',
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
        $fornecedor = $this->fornecedorBO->buscarFornecedor();
        $response = $this->produtoBO->buscarTodosProdutos($fornecedor->id);
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

    public function buscarDepartamentos() {
        $response = $this->produtoBO->buscarDepartamentos();
        return response()->json($response);
    }


    public function buscarProdutosVenda() {
        $areasRelacionadas = $this->lojaBO->buscarAreasRelacionadasVenda();
        $produtos  = $this->produtoBO->buscarProdutosVenda($areasRelacionadas);
        return response()->json($produtos);
    }
}
