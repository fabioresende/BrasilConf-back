<?php

namespace App\Http\Controllers;

use App\Http\Business\FornecedorBO;
use App\Http\Business\LojaBO;
use App\Http\Business\PedidoBO;
use App\Http\Business\ProdutoBO;
use App\Http\Business\UsuarioBO;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PedidoController extends Controller
{
    private $pedidoBO;
    private $lojaBO;
    private $fornecedorBO;
    private $produtoBO;
    public function __construct() {
        $this->pedidoBO = new PedidoBO();
        $this->lojaBO = new LojaBO();
        $this->fornecedorBO = new FornecedorBO();
        $this->produtoBO = new ProdutoBO();
    }

    protected function pedidoValidator($request) {
        $validator = Validator::make($request->all(), [
            'produto_id' => 'required|int',
            'quantidade' => 'required|max:50',
            'valor_total' => 'required'
        ]);

        return $validator;
    }
    public function buscarPedidos() {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->tipo_empresa == 2) {
            $loja = $this->lojaBO->buscarLoja();
            $response = $this->pedidoBO->buscarTodosPedidosLoja($loja);
        } elseif ($usuarioLogado->tipo_empresa == 1) {
            $fornecedor = $this->fornecedorBO->buscarFornecedor();
            $response = $this->pedidoBO->buscarTodosPedidosFornecedor($fornecedor);
        }
        return response()->json($response);
    }

    public function salvarPedido(Request $atributos) {
        $validator = $this->pedidoValidator($atributos);
        if($validator->fails() ) {
            return response()->json([
                'message'   => 'Erros de validacao do produto',
                'erros'        => $validator->errors()
            ], 422);
        }
        $loja = $this->lojaBO->buscarLoja();
        $fornecedor = $this->produtoBO->buscarFornecedorProduto($atributos->produto_id);
        if (!$loja){
            return response()->json([
                'message'   => 'Seu usuário ainda não possui loja cadastrada',
                'erro'        => true
            ], 422);
        }
        $response = $this->pedidoBO->salvar($atributos,$loja->id,$fornecedor->id);
        return response()->json($response,200);
    }


    public function buscarPedido($idPedido) {
        $pedido = $this->pedidoBO->buscarPedido($idPedido);
        return response()->json($pedido,200);
    }

    public function confirmarPedido(Request $atributos){
        $fornecedor = $this->fornecedorBO->buscarFornecedor();
        if (!$fornecedor) {
            return response()->json([
                'msg'   => 'Seu usuário ainda não possui fornecedor cadastrado',
                'success'        => true
            ], 422);
        }
        $retorno = $this->pedidoBO->confirmarPedido($atributos,$fornecedor);
        return response()->json($retorno,200);
    }

    public function finalizarPedido(Request $atributos){
        $loja = $this->lojaBO->buscarLoja();
        if (!$loja) {
            return response()->json([
                'msg'   => 'Seu usuário ainda não possui fornecedor cadastrado',
                'success'        => true
            ], 422);
        }
        $retorno = $this->pedidoBO->finalizarPedido($atributos->id_pedido,$loja);
        return response()->json($retorno,200);
    }
}
