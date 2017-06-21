<?php
namespace App\Http\Business;

use App\Departamento;
use App\Fornecedor;
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
            $resposta = $this->salvarProduto($produto,$atributos);
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
            $resposta['mensagem'] = "Produto salvo com sucesso!";
            $resposta['success'] = true;
            return $resposta;
        }
        else {
            $resposta['mensagem'] = "Erro: Não foi possível salvar o produto!";
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
        $produtoEncontrado = $produto::with(["departamento","fornecedor"])->find($idProduto);

        if (!$produtoEncontrado) {
            $resposta['mensagem'] = "Usuário não encontrado!";
            $resposta['success'] = true;
            return $resposta;
        } else {
            return $produtoEncontrado;
        }

    }

    public function buscarTodosProdutos() {
        $usuarioLogado = JWTAuth::toUser();
        if ($usuarioLogado->tipo_empresa == 1) {
            $idFornecedor = $usuarioLogado->id_fornecedor;
            $produtosVinculados = Produto::with("departamento")->where('id_fornecedor',$idFornecedor)->get();
            return $produtosVinculados;
        } else {
            $idLoja = $usuarioLogado->id_loja;
            $produtosVinculados = Produto::with("departamento")->where('id_loja',$idLoja)->get();
            return $produtosVinculados;
        }
    }

    public function buscarDepartamentos() {
        $departamentos = Departamento::all();
        return $departamentos;
    }

    public function buscarProdutosVenda($areasRelacionadasLoja) {
        $registros = DB::table('produtos')
            ->join('departamentos', 'produtos.id_departamento', '=', 'departamentos.id')
            ->join('areas', 'departamentos.id_area', '=', 'areas.id')
            ->whereIn('areas.id',$areasRelacionadasLoja)
            ->select("produtos.*")->where('produtos.status',1)
            ->get();
        foreach ($registros as $registro){
            $registro->fornecedor = Fornecedor::where('id',$registro->id_fornecedor)->first();
            $registro->deparamento = Fornecedor::where('id',$registro->id_departamento)->first();
        }
        return $registros;
    }
}