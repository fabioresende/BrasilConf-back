<?php
namespace App\Http\Business;

use App\Departamento;
use App\Fornecedor;
use App\Loja;
use App\Pedido;
use App\Usuario;
use App\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Created by PhpStorm.
 * User: fabri_000
 * Date: 12/06/2017
 * Time: 16:28
 */

class PedidoBO {


    /**
     * ProdutoBO constructor.
     */
    public function __construct()
    {
    }

    public function salvar($atributos,$loja_id,$fornecedor_id) {
        $pedido = new Pedido();
        $pedido->loja_id = $loja_id;
        $pedido->fornecedor_id = $fornecedor_id;
        $pedido->status = 'PENDENTE';
        $produtoEncontrado = Produto::find($atributos->produto_id);
        if (!$produtoEncontrado) {
            return ['msg' => 'Produto não encontrado','success' => 'Erro'];
        }
        $pedidoEncontrado = $pedido->find($atributos->id);
        if (!$pedidoEncontrado) {
            $resposta = $this->salvarPedido($pedido,$atributos);
        }
        else {
            $resposta = $this->atualizarPedido($pedidoEncontrado,$atributos);
        }
        return $resposta;
    }

    private function salvarPedido($pedido,$atributos) {
        $pedido->fill($atributos->all());
        $retorno = $pedido->save();

        if ($retorno) {
            $resposta['msg'] = "Pedido salvo com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
        }
        else {
            $resposta['msg'] = "Não foi possível salvar o pedido!";
            $resposta['success'] = "Erro";
            return $resposta;
        }
    }



    public function atualizarPedido($pedido,$atributos) {
        $pedido->fill($atributos->all());
        $controle = $pedido->save();
        if ($controle) {
            $resposta['msg'] = "Pedido atualizado com sucesso!";
            $resposta['success'] = "Sucesso";
            return $resposta;
        }
        else {
            $resposta['msg'] = "Erro: Não foi possível atualizar pedido!";
            $resposta['success'] = "Erro";
            return $resposta;
        }
    }

    public function buscarTodosPedidosLoja($loja) {
        $pedidos = Pedido::where("loja_id",$loja->id)->get();
        foreach ($pedidos as $pedido) {
            $idProduto = $pedido->produto_id;
            $pedido->produto = Produto::where("id",$idProduto)->first();
        }
        return $pedidos;
    }

    public function buscarTodosPedidosFornecedor($fornecedor) {
        $pedidos = Pedido::where("fornecedor_id",$fornecedor->id)->get();
        foreach ($pedidos as $pedido) {
            $idProduto = $pedido->produto_id;
            $pedido->produto = Produto::where("id",$idProduto)->first();
        }
        return $pedidos;
    }

    public function buscarPedido($idPedido) {
        $pedido = Pedido::find($idPedido);
        $pedido->loja = Loja::find($pedido->loja_id);
        $pedido->produto = Produto::find($pedido->produto_id);
        return $pedido;
    }

    public function confirmarPedido($atributos,$fornecedor) {
        if ($fornecedor->id == $atributos->fornecedor_id) {
            $pedido = Pedido::find($atributos->id);
            if ($pedido->status == 'PENDENTE') {
                $pedido->status = 'CONFIRMADO';
                $pedido->save();
                $resposta['msg'] = "Pedido confirmado com sucesso!";
                $resposta['success'] = "Sucesso";
                return $resposta;
            } else {
                $resposta['msg'] = "Pedido não se encontra no status para ser confirmado!";
                $resposta['success'] = "Erro";
                return $resposta;
            }
        } else{
            $resposta['msg'] = "Você não tem permissao para confirmar o pedido!";
            $resposta['success'] = "Erro";
            return $resposta;
        }
    }

    public function finalizarPedido($atributos,$loja) {
        if ($loja->id == $atributos->loja_id){
            $pedido = Pedido::find($atributos->id);
            if ($pedido->status == 'CONFIRMADO') {
                $pedido->status = 'FINALIZADO';
                $pedido->save();
                $resposta['msg'] = "Pedido finalizado com sucesso!";
                $resposta['success'] = "Sucesso";
            } else {
                $resposta['msg'] = "Pedido se encontra em status para ser finalizado!";
                $resposta['success'] = "Erro";
                return $resposta;
            }
        } else{
            $resposta['msg'] = "Você não tem permissao para finalizar o pedido!";
            $resposta['success'] = "Erro";
            return $resposta;
        }
    }
}