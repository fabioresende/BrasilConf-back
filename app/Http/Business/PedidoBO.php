<?php
namespace App\Http\Business;

use App\Departamento;
use App\Fornecedor;
use App\Loja;
use App\Pedido;
use App\Score;
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
                $produto = Produto::find($atributos->produto_id);
                $produto->quantidade = $produto->quantidade - $atributos->quantidade;
                $score = DB::transaction(function() use ($pedido,$produto,$atributos) {
                    $pedido->save();
                    $produto->save();
                    return $this->salvarScore($atributos);
                });
                $resposta['msg'] = "Pedido finalizado com sucesso foi ao seu score $score pontos!";
                $resposta['success'] = "Sucesso";
                return $resposta;
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

    public function salvarScore($atributos){
        $pontos = intVal($atributos->valor_total);
        $score = new Score();
        $fornecedor = Fornecedor::find($atributos->fornecedor_id);
        $scoreFornecedor = $fornecedor->score;
        $score->pontos = $scoreFornecedor + $pontos;
        $score->id_empresa = $atributos->fornecedor_id;
        $score->tipo_empresa = 1;
        $score->save();
        $fornecedor->score = $scoreFornecedor + $pontos;
        $fornecedor->save();

        $loja = Loja::find($atributos->loja_id);
        $scoreLoja = $loja->score;
        $score->pontos = $scoreLoja + $pontos;
        $score->id_empresa = $atributos->loja_id;
        $score->tipo_empresa = 2;
        $score->save();
        $loja->score = $scoreLoja + $pontos;
        $loja->save();
        return $pontos;
    }

    public function getPedidosPendentesLoja($loja) {
        $pedidos = Pedido::where("loja_id",$loja->id)->where("status","!=","FINALIZADO")->get();
        if(!$pedidos){
            return false;
        }
        foreach ($pedidos as $pedido) {
            $idProduto = $pedido->produto_id;
            $pedido->produto = Produto::where("id",$idProduto)->first();
        }
        return $pedidos;
    }
    public function getPedidosPendentesFornecedor($fornecedor) {
        $pedidos = Pedido::where("fornecedor_id",$fornecedor->id)->where("status","!=","FINALIZADO")->get();
        if (!$pedidos) {
            return false;
        }
        foreach ($pedidos as $pedido) {
            $idProduto = $pedido->produto_id;
            $pedido->produto = Produto::where("id",$idProduto)->first();
        }
        return $pedidos;
    }

    public function getPedidosConcluidosLoja($loja) {
        $retorno = null;
        for ($i = 1;$i<=12;$i++) {
            $pedidosMes = Pedido::where('loja_id',$loja->id)
                ->where('status','FINALIZADO')
                ->whereMonth('updated_at', '=', $i)
                ->get();
            $retorno[$i] = count($pedidosMes);
        }
        return $retorno;
    }

    public function getPedidosConcluidosFornecedor($fornecedor) {
        $retorno = null;
        for ($i = 1;$i<=12;$i++) {
            $pediosMes = Pedido::where('fornecedor_id',$fornecedor->id)
                ->where('status','FINALIZADO')
                ->whereMonth('updated_at', '=', $i)
                ->get();
            $retorno[$i] = count($pediosMes);
        }
        return $retorno;
    }
}