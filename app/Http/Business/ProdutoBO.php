<?php
namespace App\Http\Business;

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

class ProdutoBO {


    /**
     * ProdutoBO constructor.
     */
    public function __construct()
    {
    }

    public function salvar($atributos) {
        $produto = new Produto();
        $produtoEncontrado = $produto->find($atributos->id);
        if (!$produtoEncontrado) {
            $resposta = $this->salvarProduto($prodtuo,$atributos);
        }
        else {
            $resposta = $this->atualizarProduto($produtoEncontrado,$atributos);
        }
        return $resposta;
    }

    private function salvarProduto($produto,$atributos) {
        $produto->fill($atributos->all());
        $produto->id_fornecedor = JWTAuth::toUser()->id_fornecedor;
        $retorno = $produto->save();

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



    public function atualizarProduto($produto,$atributos) {
        $produto->fill($atributos->all());
        $controle = $produto->save();
        if ($controle) {
            $resposta['mensagem'] = "Produto atualizado com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível atualizar produto!";
            $resposta['success'] = false;
            return $resposta;
        }
    }

    public function buscarProduto($idProduto){
        $produto = new Produto();
        $produtoEncontrado = $produto->find($idProduto);

        if (!$produtoEncontrado) {
            $resposta['mensagem'] = "Usuário não encontrado!";
            $resposta['success'] = true;
            return $resposta;
        } else {
            return $produtoEncontrado;
        }

    }

    public function buscarTodosProdutos() {
        $idFornecedor = JWTAuth::toUser();
        $produtosVinculados = Produto::where('id_fornecedor',$idFornecedor)->get();
        return $produtosVinculados;
    }
}